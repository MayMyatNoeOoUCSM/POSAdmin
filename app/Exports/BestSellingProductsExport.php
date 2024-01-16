<?php

namespace App\Exports;

use App\Contracts\Services\ReportServiceInterface;
use App\Models\Sale;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BestSellingProductsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $reportService;
    private $request;
    private $data;
    /**
     * Create a new controller instance.
     * @param SaleServiceInterface $SaleService
     *
     * @return void
     */
    public function __construct(ReportServiceInterface $reportService, Request $request)
    {
        $this->reportService = $reportService;
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = $this->reportService->getDataExport($this->request);
        return $data;
    }

    public function headings(): array
    {
        return [
            'Shop Name',
            'Shop ID',
            'Product Name',
            'Total Amount',
            'Product Code',
           
        ];
    }

    public function map($data): array
    {
        // return [
        //     $data->product_code,
        //     $data->name,
        //     $data->shop_name,
        //     $data->sum,
        // ];
    }
}
