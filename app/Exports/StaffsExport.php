<?php

namespace App\Exports;

use App\Contracts\Services\StaffServiceInterface;
use App\Models\Staff;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StaffsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $staffService;
    private $request;

    /**
     * Create a new controller instance.
     * @param StaffServiceInterface $staffService
     *
     * @return void
     */
    public function __construct(StaffServiceInterface $staffService, Request $request)
    {
        $this->staffService = $staffService;
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $staffList = $this->staffService->getStaffListForExport($this->request);
        return $staffList;
    }

    public function headings(): array
    {
        return [
            'Staff Number',
            'Staff Role',
            'Staff Type',
            'Position',
            'Bank Account Number',
            'Graduated University',
            'Staff Name',
            'Gender',
            'NRC Number ',
            'Date Of Birth',
            'Martial Status',
            'Race',
            'City',
            'Address',
            'Phone 1 ',
            'Phone 2 ',
            'Join From',
            'Join To',
            'Staff Status',
        ];
    }
}
