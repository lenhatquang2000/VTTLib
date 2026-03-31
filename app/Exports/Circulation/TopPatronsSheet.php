<?php

namespace App\Exports\Circulation;

use App\Models\PatronDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class TopPatronsSheet implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    public function collection()
    {
        return PatronDetail::with('user', 'patronGroup')
            ->withCount('loanTransactions')
            ->orderBy('loan_transactions_count', 'desc')
            ->limit(100)
            ->get();
    }

    public function title(): string
    {
        return 'Top ban doc';
    }

    public function headings(): array
    {
        return [
            'Ho ten',
            'Ma ban doc',
            'Nhom ban doc',
            'Tong luot muon'
        ];
    }

    public function map($patron): array
    {
        return [
            $patron->user->name ?? '',
            $patron->patron_code ?? '',
            $patron->patronGroup->name ?? '',
            $patron->loan_transactions_count
        ];
    }
}
