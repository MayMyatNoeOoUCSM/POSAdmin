<?php

namespace App\Services;

use App\Contracts\Dao\SaleReturnDaoInterface;
use App\Contracts\Dao\ShopDaoInterface;
use App\Contracts\Dao\StaffDaoInterface;
use App\Contracts\Services\DamageDetailServiceInterface;
use App\Contracts\Services\DamageLossServiceInterface;
use App\Contracts\Services\SaleReturnServiceInterface;
use App\Contracts\Services\SaleServiceInterface;
use Illuminate\Support\Facades\Auth;

class SaleReturnService implements SaleReturnServiceInterface
{
    private $returnDao;
    private $damageService;
    private $damageDetailService;
    private $salesService;
    private $staffDao;
    private $shopDao;

    /**
     * Class Constructor
     * @param \App\Contracts\Dao\SaleReturnDaoInterface $returnDao
     * @param \App\Contracts\Dao\DamageLossServiceInterface $damageService
     * @param \App\Contracts\Dao\DamageDetailServiceInterface $damageDetailService
     * @param \App\Contracts\Dao\SaleServiceInterface $salesService
     * @param \App\Contracts\Dao\SaleReturnDaoInterface $returnDao
     * @param \App\Contracts\Dao\StaffDaoInterface $staffDao
     * @param \App\Contracts\Dao\ShopDaoInterface $shopDao
     *
     * @return
     */
    public function __construct(SaleReturnDaoInterface $returnDao, DamageLossServiceInterface $damageService, DamageDetailServiceInterface $damageDetailService, SaleServiceInterface $salesService, StaffDaoInterface $staffDao, ShopDaoInterface $shopDao)
    {
        $this->returnDao = $returnDao;
        $this->damageService = $damageService;
        $this->damageDetailService = $damageDetailService;
        $this->salesService = $salesService;
        $this->staffDao = $staffDao;
        $this->shopDao = $shopDao;
    }

    /**
     * Get sale return list
     *
     * @return Object $returnList
     */
    public function getSaleReturnList($request)
    {
        return $this->returnDao->getSaleReturnList($request);
    }

    /**
     * Store sale return info in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return Integer
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
            if (Auth::guard('staff')->user()->shop_id != $request->shop_id) {
                return false;
            }
        }
        return $this->returnDao->insert($request);
    }

    /**
     * Get total sale return quantity for today
     *
     * @return Integer
     */
    public function getSaleReturnByToday()
    {
        return $this->returnDao->getSaleReturnByToday();
    }

    /**
     * Get sale return details info search by return id
     *
     * @param  Integer $return_id
     * @return Object
     */
    public function getSaleReturnDetails($id)
    {
        return $this->returnDao->getSaleReturnDetails($id);
    }

    /**
     * Get sale return list info for export excel report
     *
     * @param  \Illuminate\Http\Request $request
     * @return Object  $saleReturnList
     */
    public function getSaleReturnDataExport($request)
    {
        return $this->returnDao->getSaleReturnDataExport($request);
    }

    /**
     * Store damage&loss
     *
     * @param  Integer $return_id
     * @param  \App\Http\Requests\SaleReturnRequest $request
     * @return Boolean
     */
    public function insertDamageLoss($return_id, $request)
    {
        $hasInserted = false;
        $insert_all_status = false;
        for ($i = 0; $i < count($request->qty); $i++) {
            $diff = 0;
            $diff = $request->qty[$i];
            if ($request->damageChkState[$i] == config('constants.DAMAGE_CHECK_FLG_ON')) {
                $diff = $request->qty[$i] - $request->damage_qty[$i];

                if ($hasInserted == false) {
                    $damage = new \stdClass();
                    $damage->damage_loss_date = $request->return_date;
                    $damage->remark = __('message.0006I');
                    $damage->return_id = $return_id;
                    $damage->shop_id = $request->shop_id;
                    $damage_loss_id = $this->damageService->insert($damage, config('constants.FRM_SALE_RETURN'));
                    if (! is_numeric($damage_loss_id)) {
                        return false;
                    }
                    $hasInserted = true;
                }
                $damageDetail = new \stdClass();
                $damageDetail->damage_loss_id = $damage_loss_id;
                $damageDetail->product_id = $request->product_id[$i];
                $damageDetail->quantity = $request->damage_qty[$i];
                $damageDetail->price = $request->price[$i];
                $damageDetail->product_status = 1;
                $damageDetail->remark = $request->reason[$i];
                $damageDetail->shop_id = $request->shop_id;
                $insertDamageDetResult = $this->damageDetailService->insertOneRecord($damageDetail, $damage_loss_id);
                if (! $insertDamageDetResult) {
                    return false;
                }
            } //end of if ($request->damageChk[$i])

            $updateDateInfo = new \stdClass();
            $updateDateInfo->product_id = $request->product_id[$i];
            $updateDateInfo->qty = $diff;
            $updateQtyResult = $this->salesService->updateQtyPlus($request->shop_id, $updateDateInfo);
            if ($updateQtyResult) {
                $insert_all_status = true;
            } else {
                $insert_all_status = false;
            }
        }
        // Insert new sale & return notification
        // if ($insert_all_status) {
        //     $staffList = $this->staffDao->getStaffListByShopIDArray([$request->shop_id]);
        //     $messageInfo = [
        //         'body' => 'New Sale & Return Process!',
        //         'type' => config('constants.NOTIFICATION_SALE_RETURN'),
        //     ];
        //     foreach ($staffList as $key => $value) {
        //         $staff = \App\Models\Staff::find($value->id);
        //         $staff->notify(new \App\Notifications\AdminNotification($messageInfo));
        //     }
        // }
        return $insert_all_status;
    }
}
