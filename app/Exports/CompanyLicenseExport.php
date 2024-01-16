<?php

namespace App\Exports;

use App\Contracts\Services\CompanyServiceInterface;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Symfony\Component\HttpFoundation\Request;

class CompanyLicenseExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $companyService;
    private $request;
    /**
     * Create a new controller instance.
     * @param ProductServiceInterface $productService
     *
     * @return void
     */
    public function __construct(CompanyServiceInterface $companyService, Request $request)
    {
        $this->companyService = $companyService;
        $this->request = $request;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = $this->companyService->getCompanyLicenseDataExport($this->request);
        return $data;
    }

    public function headings(): array
    {
        return [
            'Company Name',
            'Start Date',
            'End Date',
            'License Type',
            'Status',
            'Payment Amount',
            'Discount Amount',
        ];
    }
}
