<?php

namespace App\Services;

use App\Models\Z3950Server;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Z3950Service
{
    protected ?Z3950Server $server = null;

    public function setServer(Z3950Server $server): self
    {
        $this->server = $server;
        return $this;
    }

    public function hasYazExtension(): bool
    {
        return function_exists('yaz_connect');
    }

    /**
     * Test connection to Z39.50 server
     */
    public function testConnection(): array
    {
        if (!$this->server) {
            return ['success' => false, 'message' => 'No server configured'];
        }

        try {
            if ($this->hasYazExtension()) {
                return $this->testYazConnection();
            } else {
                // Simulate test for servers without YAZ
                return $this->testSocketConnection();
            }
        } catch (\Exception $e) {
            $this->server->updateStatus('failed', $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Test using YAZ extension
     */
    protected function testYazConnection(): array
    {
        $conn = @yaz_connect(
            $this->server->host . ':' . $this->server->port . '/' . $this->server->database_name,
            ['user' => $this->server->username, 'password' => $this->server->password]
        );

        if (!$conn) {
            $this->server->updateStatus('failed', 'Cannot connect to server');
            return ['success' => false, 'message' => 'Cannot connect to server'];
        }

        yaz_syntax($conn, $this->server->record_syntax);
        yaz_range($conn, 1, 1);
        yaz_search($conn, 'rpn', '@attr 1=4 test');
        yaz_wait();

        $error = yaz_error($conn);
        yaz_close($conn);

        if ($error) {
            $this->server->updateStatus('failed', $error);
            return ['success' => false, 'message' => $error];
        }

        $this->server->updateStatus('success');
        return ['success' => true, 'message' => 'Connection successful'];
    }

    /**
     * Test using socket connection (fallback)
     */
    protected function testSocketConnection(): array
    {
        $socket = @fsockopen(
            $this->server->host,
            $this->server->port,
            $errno,
            $errstr,
            $this->server->timeout
        );

        if (!$socket) {
            $this->server->updateStatus('failed', "Connection failed: $errstr ($errno)");
            return ['success' => false, 'message' => "Connection failed: $errstr"];
        }

        fclose($socket);
        $this->server->updateStatus('success');
        return ['success' => true, 'message' => 'Port is reachable (YAZ extension not installed for full test)'];
    }

    /**
     * Search Z39.50 server
     */
    public function search(string $query, string $searchType = 'keyword', int $maxRecords = 20): array
    {
        if (!$this->server) {
            return ['success' => false, 'message' => 'No server configured', 'records' => []];
        }

        if (!$this->hasYazExtension()) {
            return [
                'success' => false,
                'message' => 'YAZ extension is not installed. Please install php-yaz to enable Z39.50 search.',
                'records' => [],
                'yaz_required' => true,
            ];
        }

        try {
            return $this->searchWithYaz($query, $searchType, $maxRecords);
        } catch (\Exception $e) {
            Log::error('Z39.50 search error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage(), 'records' => []];
        }
    }

    /**
     * Search using YAZ extension
     */
    protected function searchWithYaz(string $query, string $searchType, int $maxRecords): array
    {
        $conn = @yaz_connect(
            $this->server->host . ':' . $this->server->port . '/' . $this->server->database_name,
            [
                'user' => $this->server->username,
                'password' => $this->server->password,
                'charset' => $this->server->charset,
            ]
        );

        if (!$conn) {
            return ['success' => false, 'message' => 'Cannot connect to server', 'records' => []];
        }

        yaz_syntax($conn, $this->server->record_syntax);
        yaz_range($conn, 1, min($maxRecords, $this->server->max_records));
        yaz_element($conn, 'F');

        // Build RPN query based on search type
        $rpnQuery = $this->buildRpnQuery($query, $searchType);
        yaz_search($conn, 'rpn', $rpnQuery);
        yaz_wait();

        $error = yaz_error($conn);
        if ($error) {
            yaz_close($conn);
            return ['success' => false, 'message' => $error, 'records' => []];
        }

        $hits = yaz_hits($conn);
        $records = [];

        for ($i = 1; $i <= min($hits, $maxRecords); $i++) {
            $record = yaz_record($conn, $i, 'raw');
            if ($record) {
                $parsed = $this->parseMarcRecord($record);
                if ($parsed) {
                    $records[] = $parsed;
                }
            }
        }

        yaz_close($conn);

        return [
            'success' => true,
            'message' => "Found $hits records",
            'total' => $hits,
            'records' => $records,
        ];
    }

    /**
     * Build RPN query for Z39.50 search
     */
    protected function buildRpnQuery(string $query, string $searchType): string
    {
        $query = addcslashes($query, '"');

        // Bib-1 attribute mappings
        $attributes = [
            'keyword' => '@attr 1=1016',      // Any
            'title' => '@attr 1=4',           // Title
            'author' => '@attr 1=1003',       // Author
            'isbn' => '@attr 1=7',            // ISBN
            'issn' => '@attr 1=8',            // ISSN
            'subject' => '@attr 1=21',        // Subject
            'publisher' => '@attr 1=1018',    // Publisher
            'year' => '@attr 1=31',           // Date of publication
            'lccn' => '@attr 1=9',            // LC control number
        ];

        $attr = $attributes[$searchType] ?? $attributes['keyword'];
        return "$attr \"$query\"";
    }

    /**
     * Parse MARC record to array
     */
    protected function parseMarcRecord(string $raw): ?array
    {
        try {
            // Basic MARC21 parsing
            if (strlen($raw) < 24) {
                return null;
            }

            $leader = substr($raw, 0, 24);
            $baseAddress = (int)substr($leader, 12, 5);

            $directory = substr($raw, 24, $baseAddress - 25);
            $data = substr($raw, $baseAddress);

            $fields = [];
            $title = '';
            $author = '';
            $isbn = '';
            $publisher = '';
            $year = '';
            $subject = [];

            // Parse directory entries (12 bytes each)
            for ($i = 0; $i < strlen($directory); $i += 12) {
                $tag = substr($directory, $i, 3);
                $length = (int)substr($directory, $i + 3, 4);
                $start = (int)substr($directory, $i + 7, 5);

                $fieldData = substr($data, $start, $length - 1);

                // Extract common fields
                switch ($tag) {
                    case '020': // ISBN
                        if (preg_match('/\$a([^\$]+)/', $fieldData, $m)) {
                            $isbn = trim($m[1]);
                        }
                        break;
                    case '100': // Author
                    case '110':
                    case '111':
                        if (preg_match('/\$a([^\$]+)/', $fieldData, $m)) {
                            $author = trim($m[1]);
                        }
                        break;
                    case '245': // Title
                        if (preg_match('/\$a([^\$]+)/', $fieldData, $m)) {
                            $title = trim($m[1]);
                        }
                        if (preg_match('/\$b([^\$]+)/', $fieldData, $m)) {
                            $title .= ' ' . trim($m[1]);
                        }
                        break;
                    case '260': // Publication
                    case '264':
                        if (preg_match('/\$b([^\$]+)/', $fieldData, $m)) {
                            $publisher = trim($m[1]);
                        }
                        if (preg_match('/\$c([^\$]+)/', $fieldData, $m)) {
                            $year = trim($m[1]);
                        }
                        break;
                    case '650': // Subject
                    case '651':
                        if (preg_match('/\$a([^\$]+)/', $fieldData, $m)) {
                            $subject[] = trim($m[1]);
                        }
                        break;
                }

                $fields[$tag][] = $fieldData;
            }

            return [
                'leader' => $leader,
                'title' => rtrim($title, ' /:.'),
                'author' => rtrim($author, ','),
                'isbn' => preg_replace('/[^0-9X]/', '', $isbn),
                'publisher' => rtrim($publisher, ','),
                'year' => preg_replace('/[^0-9]/', '', $year),
                'subjects' => $subject,
                'fields' => $fields,
                'raw' => base64_encode($raw),
            ];
        } catch (\Exception $e) {
            Log::error('MARC parse error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Import MARC record to bibliographic_records
     */
    public function importRecord(array $marcData): ?int
    {
        // This will be implemented to save to bibliographic_records table
        // For now, return null - will be connected to existing cataloging system
        return null;
    }
}
