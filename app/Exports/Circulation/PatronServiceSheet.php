<?php

namespace App\Exports\Circulation;

use App\Models\PatronDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;

class PatronServiceSheet implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    public function collection()
    {
        return PatronDetail::select('patron_groups.name as group_name', DB::raw('count(*) as total'))
            ->join('patron_groups', 'patron_details.patron_group_id', '=', 'patron_groups.id')
            ->groupBy('patron_groups.name')
            ->get();
    }

    public function title(): string
    {
        return 'Thong ke phuc vu';
    }

    public function headings(): array
    {
        return [
            'Nhom ban doc',
            'So luong ban doc'
        ];
    }

    public function map($stat): array
    {
        return [
            $stat->group_name,
            $stat->total
        ];
    }
}
