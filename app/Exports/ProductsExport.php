<?php

namespace App\Exports;

use App\Contracts\Services\ProductServiceInterface;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $productService;
    private $request;
    /**
     * Create a new controller instance.
     * @param ProductServiceInterface $productService
     *
     * @return void
     */
    public function __construct(ProductServiceInterface $productService, Request $request)
    {
        $this->productService = $productService;
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = $this->productService->getProductDataExport($this->request);
        return $data;
    }

    public function headings(): array
    {
        return [
            'Shop Name',
            'Product Name',
            'Stock Quantity',
        ];
    }
}
