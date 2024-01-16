<?php

namespace App\Exports;

use App\Contracts\Services\SaleReturnServiceInterface;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Symfony\Component\HttpFoundation\Request;

class SaleReturnExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $saleReturnService;
    private $request;
    /**
     * Create a new controller instance.
     * @param SaleReturnServiceInterface $saleReturnService
     *
     * @return void
     */
    public function __construct(SaleReturnServiceInterface $saleReturnService, Request $request)
    {
        $this->saleReturnService = $saleReturnService;
        $this->request = $request;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = $this->saleReturnService->getSaleReturnDataExport($this->request);
        return $data;
    }

    public function headings(): array
    {
        return [
            'Sale Date',
            'Return Date',
            'Return Quantity',
        ];
    }
}
