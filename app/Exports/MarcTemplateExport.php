<?php

namespace App\Exports;

use App\Models\MarcFramework;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;

class MarcTemplateExport implements FromView, WithTitle
{
    protected $framework;

    public function __construct(MarcFramework $framework)
    {
        $this->framework = $framework;
    }

    public function view(): View
    {
        return view('admin.marc_import.template', [
            'framework' => $this->framework
        ]);
    }

    public function title(): string
    {
        return 'MARC Import Template - ' . $this->framework->code;
    }
}
