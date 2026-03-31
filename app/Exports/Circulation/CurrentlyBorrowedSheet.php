<?php

namespace App\Exports\Circulation;

use App\Models\LoanTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class CurrentlyBorrowedSheet implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return LoanTransaction::with(['patron.user', 'bookItem.bibliographicRecord'])
            ->where('status', 'borrowed')
            ->get();
    }

    public function title(): string
    {
        return 'Dang muon';
    }

    public function headings(): array
    {
        return [
            'Ma vach',
            'Nhan de',
            'Ban doc',
            'Ma ban doc',
            'Ngay muon',
            'Han tra',
            'Trang thai'
        ];
    }

    /**
     * @param mixed $loan
     * @return array
     */
    public function map($loan): array
    {
        return [
            $loan->bookItem->barcode,
            $loan->bookItem->bibliographicRecord->title ?? '',
            $loan->patron->user->name ?? '',
            $loan->patron->patron_code ?? '',
            $loan->loan_date,
            $loan->due_date,
            $loan->status
        ];
    }
}
