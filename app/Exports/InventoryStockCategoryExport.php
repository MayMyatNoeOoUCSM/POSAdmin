<?php

namespace App\Exports;

use App\Contracts\Services\CategoryServiceInterface;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InventoryStockCategoryExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $categoryService;
    private $request;
    /**
     * Create a new controller instance.
     * @param CategoryServiceInterface $categoryService
     *
     * @return void
     */
    public function __construct(CategoryServiceInterface $categoryService, Request $request)
    {
        $this->categoryService = $categoryService;
        $this->request = $request;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = $this->categoryService->getInventoryStockCategoryDataExport($this->request);
        return $data;
    }

    public function headings(): array
    {
        return [
            'Shop Name',
            'Category Name',
            'Stock Quantity',
        ];
    }
}
