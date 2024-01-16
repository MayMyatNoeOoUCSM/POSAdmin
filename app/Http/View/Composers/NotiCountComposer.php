<?php

namespace App\Http\View\Composers;

use App\Contracts\Dao\DamageLossDaoInterface;
use App\Contracts\Dao\ProductDaoInterface;
use App\Contracts\Dao\SaleReturnDaoInterface;
use App\Contracts\Dao\StockDaoInterface;
use Illuminate\View\View;

class NotiCountComposer
{
    private $stockDao;
    private $productDao;

    /**
     * Class Constructor
     * @param StockDaoInterface
     * @return
     */
    public function __construct(StockDaoInterface $stockDao, ProductDaoInterface $productDao, SaleReturnDaoInterface $saleReturnDao, DamageLossDaoInterface $damageLossDao)
    {
        $this->stockDao         = $stockDao;
        $this->productDao       = $productDao;
        $this->saleReturnDao    = $saleReturnDao;
        $this->damageLossDao    = $damageLossDao;
    }
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        /**
        $lowStock   = $this->stockDao->getLowStock();
        if (session('lowStock')) {
            if (session('checknoti')=='check') {
                session(['lowStock'=>$this->stockDao->getLowStock()]);
                $lowStock = 0;
                session(['checknoti'=>'no']);
            } else {
                if ($lowStock == session('lowStock')) {
                    $lowStock = 0;
                }
                if ($lowStock > session('lowStock')) {
                    $lowStock = $lowStock - session('lowStock');
                }
            }
        } else {
            session(['lowStock'=>$lowStock]);
        }

        $newproducttoday    = $this->productDao->getNewProductByToday();
        if (session('newproducttoday')) {
            if (session('checknoti')=='check') {
                session(['newproducttoday'=>$this->productDao->getNewProductByToday()]);
                $newproducttoday = 0;
                session(['checknoti'=>'no']);
            } else {
                if ($newproducttoday == session('newproducttoday')) {
                    $newproducttoday = 0;
                }
                if ($newproducttoday > session('newproducttoday')) {
                    $newproducttoday = $newproducttoday - session('newproducttoday');
                }
            }
        } else {
            session(['newproducttoday'=>$newproducttoday]);
        }

        $saleReturn = $this->saleReturnDao->getSaleReturnByToday();
        if (session('saleReturn')) {
            if (session('checknoti')=='check') {
                session(['saleReturn'=>$this->saleReturnDao->getSaleReturnByToday()]);
                $saleReturn = 0;
                session(['checknoti'=>'no']);
            } else {
                if ($saleReturn == session('saleReturn')) {
                    $saleReturn = 0;
                }
                if ($saleReturn > session('saleReturn')) {
                    $saleReturn = $saleReturn - session('saleReturn');
                }
            }
        } else {
            session(['saleReturn'=>$saleReturn]);
        }

        $damageLoss = $this->damageLossDao->getDamageLossByToday();
        if (session('damageLoss')) {
            if (session('checknoti')=='check') {
                session(['damageLoss'=>$this->damageLossDao->getDamageLossByToday()]);
                $damageLoss = 0;
                session(['checknoti'=>'no']);
            } else {
                if ($damageLoss == session('damageLoss')) {
                    $damageLoss = 0;
                }
                if ($damageLoss > session('damageLoss')) {
                    $damageLoss = $damageLoss - session('damageLoss');
                }
            }
        } else {
            session(['damageLoss'=>$damageLoss]);
        }

        $total      = $lowStock+$newproducttoday+$saleReturn+$damageLoss;

        $view->with('lowStock', $lowStock)
            ->with('product', $newproducttoday)
            ->with('saleReturn', $saleReturn)
            ->with('damageLoss', $damageLoss)
            ->with('total', $total);
        **/
    }
}
