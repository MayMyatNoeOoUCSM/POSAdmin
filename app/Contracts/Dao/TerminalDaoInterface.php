<?php

namespace App\Contracts\Dao;

/**
 * TerminalDaoInterface
 */
interface TerminalDaoInterface
{

    /**
     * Get Terminal List from storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object terminalList
     */
    public function getTerminalList($request);

    /**
     * Delete Terminal By Id from storage
     *
     * @param Object $terminal
     * @return Object $terminal
     */
    public function delete($terminal);

    /**
     * Get Shop Id by Terminal Id from storage
     *
     * @param Integer $terminalId
     * @return Integer shop_id
     */
    public function getShopId($terminalId);

    /**
     * Terminal info save into storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $terminal
     */
    public function insert($request);

    /**
     * Terminal info update into storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $terminal
     */
    public function update($request);
}
