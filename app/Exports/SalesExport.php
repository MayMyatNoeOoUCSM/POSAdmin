<?php

namespace App\Exports;

use App\Contracts\Services\SaleServiceInterface;
use App\Models\Sale;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $saleService;
    private $request;

    /**
     * Create a new controller instance.
     * @param SaleServiceInterface $SaleService
     *
     * @return void
     */
    public function __construct(SaleServiceInterface $saleService, Request $request)
    {
        $this->saleService = $saleService;
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $saleList = $this->saleService->getSaleListForExport($this->request);
        return $saleList;
    }

    public function headings(): array
    {
        return [
            'Sale Date',
            'Invoice No',
            'Shop Name',
            'Terminal Name',
            'Amount',
            'Tax Amount',
            'Staff Name',
            'Remark',
            'Total Amount'


            // 'Sale Number',
            // 'Sale Role',
            // 'Sale Type',
            // 'Position',
            // 'Bank Account Number',
            // 'Graduated University',
            // 'Sale Name',
            // 'Gender',
            // 'NRC Number ',
            // 'Date Of Birth',
            // 'Martial Status',
            // 'Race',
            // 'City',
            // 'Address',
            // 'Phone 1 ',
            // 'Phone 2 ',
            // 'Join From',
            // 'Join To',
            // 'Sale Status',
        ];
    }
}
