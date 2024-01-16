<?php

namespace App\Exports;

use App\Contracts\Services\SaleServiceInterface;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Symfony\Component\HttpFoundation\Request;

class TopSaleProductExport implements FromCollection, WithHeadings, ShouldAutoSize
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
        $saleProductList = $this->saleService->getTopSaleProductListForExport($this->request);
        return $saleProductList;
    }

    public function headings(): array
    {
        return [
            'Shop Name',
            'Product Name',
            'Total Sale Quantity',
        ];
    }
}
