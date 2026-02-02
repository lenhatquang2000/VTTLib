<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Z3950Server;
use App\Services\Z3950Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class Z3950Controller extends Controller
{
    protected Z3950Service $z3950Service;

    public function __construct(Z3950Service $z3950Service)
    {
        $this->z3950Service = $z3950Service;
    }

    /**
     * Display list of Z39.50 servers
     */
    public function index()
    {
        $servers = Z3950Server::ordered()->get();
        $hasYaz = $this->z3950Service->hasYazExtension();

        return view('admin.z3950.index', compact('servers', 'hasYaz'));
    }

    /**
     * Store a new Z39.50 server
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'database_name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'charset' => 'required|string|max:50',
            'record_syntax' => 'required|string|max:50',
            'description' => 'nullable|string',
            'timeout' => 'required|integer|min:5|max:120',
            'max_records' => 'required|integer|min:10|max:500',
            'is_active' => 'boolean',
            'use_ssl' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['use_ssl'] = $request->has('use_ssl');

        Z3950Server::create($validated);

        return redirect()->route('admin.z3950.index')
            ->with('success', __('Z39.50 server created successfully.'));
    }

    /**
     * Update Z39.50 server
     */
    public function update(Request $request, Z3950Server $server)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'database_name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'charset' => 'required|string|max:50',
            'record_syntax' => 'required|string|max:50',
            'description' => 'nullable|string',
            'timeout' => 'required|integer|min:5|max:120',
            'max_records' => 'required|integer|min:10|max:500',
            'is_active' => 'boolean',
            'use_ssl' => 'boolean',
            'order' => 'integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['use_ssl'] = $request->has('use_ssl');

        // Only update password if provided
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $server->update($validated);

        return redirect()->route('admin.z3950.index')
            ->with('success', __('Z39.50 server updated successfully.'));
    }

    /**
     * Delete Z39.50 server
     */
    public function destroy(Z3950Server $server)
    {
        $server->delete();

        return redirect()->route('admin.z3950.index')
            ->with('success', __('Z39.50 server deleted successfully.'));
    }

    /**
     * Test connection to Z39.50 server
     */
    public function testConnection(Z3950Server $server)
    {
        $result = $this->z3950Service->setServer($server)->testConnection();

        return response()->json($result);
    }

    /**
     * Search page
     */
    public function search()
    {
        $servers = Z3950Server::active()->ordered()->get();
        $hasYaz = $this->z3950Service->hasYazExtension();

        return view('admin.z3950.search', compact('servers', 'hasYaz'));
    }

    /**
     * Perform search on Z39.50 server
     */
    public function doSearch(Request $request)
    {
        $request->validate([
            'server_id' => 'required|exists:z3950_servers,id',
            'query' => 'required|string|min:2|max:255',
            'search_type' => 'required|in:keyword,title,author,isbn,issn,subject,publisher,year',
            'max_records' => 'integer|min:5|max:100',
        ]);

        $server = Z3950Server::findOrFail($request->server_id);
        $maxRecords = $request->input('max_records', 20);

        $result = $this->z3950Service
            ->setServer($server)
            ->search($request->query, $request->search_type, $maxRecords);

        return response()->json($result);
    }

    /**
     * Import MARC record from search results
     */
    public function import(Request $request)
    {
        $request->validate([
            'raw' => 'required|string',
        ]);

        try {
            $raw = base64_decode($request->raw);
            
            // Parse and prepare for cataloging
            // This will redirect to cataloging form with pre-filled data
            $marcData = $this->parseMarcForImport($raw);

            return response()->json([
                'success' => true,
                'message' => __('Record parsed successfully. Ready for import.'),
                'data' => $marcData,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Parse MARC record for import to cataloging system
     */
    protected function parseMarcForImport(string $raw): array
    {
        $fields = [];
        
        if (strlen($raw) < 24) {
            throw new \Exception('Invalid MARC record');
        }

        $leader = substr($raw, 0, 24);
        $baseAddress = (int)substr($leader, 12, 5);
        $directory = substr($raw, 24, $baseAddress - 25);
        $data = substr($raw, $baseAddress);

        // Parse all fields
        for ($i = 0; $i < strlen($directory); $i += 12) {
            $tag = substr($directory, $i, 3);
            $length = (int)substr($directory, $i + 3, 4);
            $start = (int)substr($directory, $i + 7, 5);
            $fieldData = substr($data, $start, $length - 1);

            if ($tag < '010') {
                // Control field
                $fields[] = [
                    'tag' => $tag,
                    'ind1' => '',
                    'ind2' => '',
                    'subfields' => [['code' => '', 'value' => $fieldData]],
                ];
            } else {
                // Data field
                $ind1 = substr($fieldData, 0, 1);
                $ind2 = substr($fieldData, 1, 1);
                $subfieldData = substr($fieldData, 2);

                $subfields = [];
                $parts = explode("\x1F", $subfieldData);
                foreach ($parts as $part) {
                    if (strlen($part) > 0) {
                        $subfields[] = [
                            'code' => substr($part, 0, 1),
                            'value' => substr($part, 1),
                        ];
                    }
                }

                $fields[] = [
                    'tag' => $tag,
                    'ind1' => $ind1 === ' ' ? '' : $ind1,
                    'ind2' => $ind2 === ' ' ? '' : $ind2,
                    'subfields' => $subfields,
                ];
            }
        }

        return [
            'leader' => $leader,
            'fields' => $fields,
        ];
    }
}
