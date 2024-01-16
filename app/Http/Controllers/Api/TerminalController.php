<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\TerminalServiceInterface;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\TerminalResource;
use Illuminate\Http\Request;

class TerminalController extends ApiController
{
    private $terminalService;
   

    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\TerminalServiceInterface $terminalService
     * @return void
     */
    public function __construct(TerminalServiceInterface $terminalService)
    {
        $this->terminalService = $terminalService;
    }

    /**
     *
     * Display a listing of the resource
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Json or Collection
     */
    public function terminalList(Request $request)
    {
        //terminal list
        $terminalList = $this->terminalService->getTerminalList($request);
        if (is_null($terminalList)) {
            return response()->json([
                'status' => 'error',
                'message' => 'There is no terminal list.',
            ]);
        }
        return TerminalResource::collection($terminalList)->additional(
            ['status'=>'success',
                'message'=>'Successfully retrieved terminal list.']
        );
    }
}
