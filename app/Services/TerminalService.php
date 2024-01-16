<?php

namespace App\Services;

use App\Contracts\Dao\ShopDaoInterface;
use App\Contracts\Dao\TerminalDaoInterface;
use App\Contracts\Services\TerminalServiceInterface;
use Auth;

class TerminalService implements TerminalServiceInterface
{
    private $terminalDao;
    private $shopDao;

    /**
     * Class Constructor
     *
     * @param App\Contracts\Dao\TerminalDaoInterface $terminalDao
     * @param App\Contracts\Dao\ShopDaoInterface $shopDao
     * @return void
     */
    public function __construct(TerminalDaoInterface $terminalDao, ShopDaoInterface $shopDao)
    {
        $this->terminalDao = $terminalDao;
        $this->shopDao     = $shopDao;
    }

    /**
     * Get terminal list from storage
     *
     * @return Object $terminalList
     */
    public function getTerminalList($request)
    {
        return $this->terminalDao->getTerminalList($request);
    }

    /**
     * Remove specified terminl from storage
     *
     * @param \App\Models\Terminal $terminal
     * @return Object $terminal
     */
    public function delete($terminal)
    {
        return $this->terminalDao->delete($terminal);
    }

    /**
     * Get shop id search by terminal id
     *
     * @param Integer $terminalId
     * @return Integer $shopId
     */
    public function getShopId($terminalId)
    {
        return $this->terminalDao->getShopId($terminalId);
    }

    /**
     * Store terminal info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $terminal
     */
    public function insert($request)
    {
        // Check shop id is owned for company admin role
        if (Auth::guard('staff')->user()->role == config('constants.COMPANY_ADMIN')) {
            $shopList = $this->shopDao->getShopTypeByCompanyID(Auth::guard('staff')->user()->company_id);
            $checkExists=$shopList->contains('id', $request->shop_id);
            if (! $checkExists) {
                return $checkExists;
            }
        }
        // Check shop id is owned for shop admin role
        if (Auth::guard('staff')->user()->role == config('constants.SHOP_ADMIN')) {
            if (Auth::guard('staff')->user()->shop_id !== $request->shop_id) {
                return false;
            }
        }

        return $this->terminalDao->insert($request);
    }

    /**
     * Update terminal info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $terminal
     */
    public function update($request)
    {
        return $this->terminalDao->update($request);
    }
}
