<?php

namespace App\Exports\Circulation;

use App\Models\BookItem;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;

class NeverBorrowedSheet implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    public function collection()
    {
        return BookItem::with(['bibliographicRecord', 'storageLocation'])
            ->whereDoesntHave('loanTransactions')
            ->get();
    }

    public function title(): string
    {
        return 'Chua muon bao gio';
    }

    public function headings(): array
    {
        return [
            'Ma vach',
            'Nhan de',
            'Kho/Vi tri',
            'Trang thai'
        ];
    }

    public function map($item): array
    {
        return [
            $item->barcode,
            $item->bibliographicRecord->title ?? '',
            $item->storageLocation->name ?? '',
            $item->status
        ];
    }
}
