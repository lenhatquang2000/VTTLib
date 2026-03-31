<?php

namespace App\Exports\Circulation;


use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Circulation\CurrentlyBorrowedSheet;
use App\Exports\Circulation\OverdueSheet;
use App\Exports\Circulation\TopPatronsSheet;
use App\Exports\Circulation\TransactionHistorySheet;
use App\Exports\Circulation\NeverBorrowedSheet;
use App\Exports\Circulation\PatronServiceSheet;

class MultiReportExport implements WithMultipleSheets
{
    protected $selectedReports;

    public function __construct(array $selectedReports)
    {
        $this->selectedReports = $selectedReports;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->selectedReports as $report) {
            switch ($report) {
                case 'currently_borrowed':
                    $sheets[] = new CurrentlyBorrowedSheet();
                    break;
                case 'overdue':
                    $sheets[] = new OverdueSheet();
                    break;
                case 'top_patrons':
                    $sheets[] = new TopPatronsSheet();
                    break;
                case 'transaction_history':
                    $sheets[] = new TransactionHistorySheet();
                    break;
                case 'never_borrowed':
                    $sheets[] = new NeverBorrowedSheet();
                    break;
                case 'patron_service':
                    // This one might need multiple sub-sheets or a summary sheet
                    $sheets[] = new PatronServiceSheet();
                    break;
            }
        }

        return $sheets;
    }
}
