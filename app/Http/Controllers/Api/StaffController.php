<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\StaffServiceInterface;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\StaffResource;
use Illuminate\Http\Request;

class StaffController extends ApiController
{
    private $staffService;
   
    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\StaffServiceInterface $staffService
     * @return void
     */
    public function __construct(StaffServiceInterface $staffService)
    {
        $this->staffService = $staffService;
    }

    /**
     *
     * Show Staff Info
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Resource or Json
     */
    public function details(Request $request)
    {
        $staffDetails = $this->staffService->getStaffDetails(auth('api')->user()->id);
        if ($staffDetails) {
            return new StaffResource($staffDetails);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Can\'t retrieved staff details',
        ]);
    }
}
