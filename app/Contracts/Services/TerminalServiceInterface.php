<?php

namespace App\Contracts\Services;

interface TerminalServiceInterface
{

    /**
     * Get terminal list from storage
     *
     * @return Object $terminalList
     */
    public function getTerminalList($request);

    /**
     * Remove specified terminl from storage
     *
     * @param \App\Models\Terminal $terminal
     * @return Object $terminal
     */
    public function delete($terminal);

    /**
     * Get shop id search by terminal id
     *
     * @param Integer $terminalId
     * @return Integer $shopId
     */
    public function getShopId($terminalId);
}
