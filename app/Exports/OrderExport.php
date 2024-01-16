<?php

namespace App\Exports;

use App\Contracts\Services\OrderServiceInterface;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $orderService;
    private $request;

    /**
     * Create a new controller instance.
     * @param OrderServiceInterface $orderService
     *
     * @return void
     */
    public function __construct(OrderServiceInterface $orderService, Request $request)
    {
        $this->orderService = $orderService;
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $orderList = $this->orderService->getOrderListForExport($this->request);
        return $orderList;
    }

    public function headings(): array
    {
        return [
            'Order Date',
            'Invoice No',
            'Shop Name',
            'Terminal Name',
            'Amount',
            'Tax Amount',
            'Restaurant Name',
            'Remark',
            'Total Amount',
            'Order Status'
        ];
    }
}
