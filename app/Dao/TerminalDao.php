<?php

namespace App\Dao;

use App\Contracts\Dao\TerminalDaoInterface;
use App\Models\Terminal;

/**
 * Terminal Dao
 *
 * @author
 */
class TerminalDao implements TerminalDaoInterface
{

    /**
     * Get terminal list from storage
     *
     * @return Object $terminalList
     */
    public function getTerminalList($request)
    {
        $terminalList = Terminal::leftJoin('m_shop as s', 's.id', '=', 'm_terminal.shop_id')
            ->where('m_terminal.is_deleted', config('constants.DEL_FLG_OFF'))
            ->paginate(config('constants.TERMINAL_PAGINATION'), ['m_terminal.*', 's.name as shop_name']);
        return $terminalList;
    }

    /**
     * Remove specified terminl from storage
     *
     * @param \App\Models\Terminal $terminal
     * @return Object $staff
     */
    public function delete($terminal)
    {
        $terminal = Terminal::where('id', $terminal->id)->update(['is_deleted' => config('constants.DEL_FLG_ON')]);
        return $terminal;
    }

    /**
     * Get shop id search by terminal id
     *
     * @param Integer $terminalId
     * @return Integer $shopId
     */
    public function getShopId($terminalId)
    {
        $shopId = Terminal::where('id', $terminalId)->pluck('shop_id');
        return $shopId;
    }

    /**
     * Store terminal info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $terminal
     */
    public function insert($request)
    {
        $terminal = new Terminal;
        $terminal->name = $request->name;
        $terminal->shop_id = $request->shop_id;
        $terminal->serial_number = 0;
        $terminal->create_user_id = auth()->user()->id;
        $terminal->update_user_id = auth()->user()->id;
        $terminal->create_datetime = Date('Y-m-d H:i:s');
        $terminal->update_datetime = Date('Y-m-d H:i:s');
        $terminal->save();
        return $terminal;
    }
    /**
     * Update terminal info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Object $terminal
     */
    public function update($request)
    {
        $terminal = Terminal::find($request->id);
        $terminal->name = $request->name;
        //$terminal->shop_id = $request->shop_id;
        $terminal->update_user_id = auth()->user()->id;
        $terminal->update_datetime = Date('Y-m-d H:i:s');
        $terminal->save();
        return $terminal;
    }
}
