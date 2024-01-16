<?php

namespace App\Exports;

use App\Contracts\Services\SaleDetailServiceInterface;
use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SaleDetailExport implements FromView, ShouldAutoSize
{
    private $saleDetailService;
    private $sale;
    private $saleDetailsList;

    /**
     * Create a new controller instance.
     * @param SaleServiceInterface $SaleService
     *
     * @return void
     */
    public function __construct($sale, $saleDetailsList)
    {
        //$this->saleDetailService = $saleDetailService;
        $this->sale = $sale;
        $this->saleDetailsList = $saleDetailsList;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    // public function collection()
    // {
    //     $saleDetailList = $this->saleDetailService->getSaleDetailListForExport($this->sale);
    //     return $saleDetailList;
    // }
    
    // public function query()
    // {
    //     //return Sale::query();
    //     $data = $this->saleDetailService->getSaleDetailListForExport($this->sale);
    //     return $data;
    // }

    /**
    * @var Invoice $invoice
    */
    // public function map($sale): array
    // {
    //     // This example will return 3 rows.
    //     // First row will have 2 column, the next 2 will have 1 column
    //     return [
    //             ['Invoice Number','Sale Date From','Staff Name','Terminal Name','Amount','Total','Reason'],
    //            $sale
        
    //     ];
    // }
    // public function array(): array
    // {
    //     return [
        
    //             $this->saleDetailService->getSaleDetailListForExport($this->sale)
    //         ,
            
    //             $this->saleDetailService->getSaleDetailListForExport($this->sale)
            
    //     ];
    // }
    // public function map($sale): array
    // {
    //     // This example will return 3 rows.
    //     // First row will have 2 column, the next 2 will have 1 column
    //     return [
    //         [
               
    //             $sale->id,
    //         ]
    //     ];
    // }
    // public function headings(): array
    // {
    //     return [

    //         ['Invoice Number','Sale Date From','Staff Name','Terminal Name','Amount','Total','Reason'],
    //         ['Product Name','Quantity','Price','Remark']
    //     ];
    // }
    
    public function view(): View
    {
        return view('sale.exportexcel', ['sale'=>$this->sale,'saleDetailsList'=>$this->saleDetailsList]);
    }
}
