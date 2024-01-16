<?php

namespace App\Exports;

use App\Contracts\Services\DamageLossServiceInterface;
use App\Models\Sale;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DamageAndLossExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $damagelossService;
    private $request;
    private $data;
    /**
     * Create a new controller instance.
     * @param DamageLossServiceInterface $SaleService
     *
     * @return void
     */
    public function __construct(DamageLossServiceInterface $damagelossService, Request $request)
    {
        $this->damagelossService = $damagelossService;
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = $this->damagelossService->getDamageLossDataExport($this->request);
        return $data;
        // var_dump($data);die;
    }

    public function headings(): array
    {
        return [
            'Shop Name',
            'Product Name',
            'Total Damage & Loss Quantity',
            'Type'
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
