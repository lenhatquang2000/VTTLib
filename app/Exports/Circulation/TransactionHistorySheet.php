<?php

namespace App\Exports\Circulation;

use App\Models\LoanTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionHistorySheet implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    public function collection()
    {
        return LoanTransaction::with(['patron.user', 'bookItem.bibliographicRecord'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function title(): string
    {
        return 'Luoc su giao dich';
    }

    public function headings(): array
    {
        return [
            'Ma vach',
            'Nhan de',
            'Ban doc',
            'Ngay muon',
            'Ngay tra/Han tra',
            'Trang thai'
        ];
    }

    public function map($txn): array
    {
        return [
            $txn->bookItem->barcode,
            $txn->bookItem->bibliographicRecord->title ?? '',
            $txn->patron->user->name ?? '',
            $txn->loan_date,
            $txn->return_date ?? $txn->due_date,
            $txn->status
        ];
    }
}
