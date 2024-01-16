<?php

namespace App\Http\Controllers;

use App\Contracts\Services\CategoryServiceInterface;
use App\Contracts\Services\CompanyServiceInterface;
use App\Contracts\Services\DamageLossServiceInterface;
use App\Contracts\Services\ProductServiceInterface;
use App\Contracts\Services\ReportServiceInterface;
use App\Contracts\Services\SaleReturnServiceInterface;
use App\Contracts\Services\SaleServiceInterface;
use App\Contracts\Services\ShopServiceInterface;
use App\Contracts\Services\WarehouseServiceInterface;
use App\Exports\BestSellingProductsExport;
use App\Exports\CompanyExport;
use App\Exports\CompanyLicenseExport;
use App\Exports\DamageAndLossExport;
use App\Exports\InventoryStockCategoryExport;
use App\Exports\InventoryStockProductExport;
use App\Exports\InvoiceDetailsExport;
use App\Exports\InvoiceExport;
use App\Exports\ProductsExport;
use App\Exports\SaleCategoryExport;
use App\Exports\SaleProductExport;
use App\Exports\SaleReturnExport;
use App\Exports\SalesByProductCategoryExport;
use App\Exports\SalesExport;
use App\Exports\TopSaleProductExport;
use App\Http\Requests\DamageLossReportRequest;
use App\Http\Requests\InventoryStockCategoryRequest;
use App\Http\Requests\InventoryStockProductRequest;
use App\Http\Requests\InvoiceDetailsReportRequest;
use App\Http\Requests\SaleCategoryReportRequest;
use App\Http\Requests\SaleProductReportRequest;
use App\Http\Requests\SaleReportRequest;
use App\Http\Requests\TopSaleProductReportRequest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    private $shopService;
    private $categoryService;
    private $warehouseService;
    private $reportService;
    private $productService;
    private $saleService;
    private $saleReturnService;
    private $damagelossService;
    private $companyService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Contracts\Services\ShopServiceInterface $shopService
     * @param \App\Contracts\Services\CategoryServiceInterface $categoryService
     * @param \App\Contracts\Services\WarehouseServiceInterface $warehouseService
     * @param \App\Contracts\Services\ProductServiceInterface $productService
     * @param \App\Contracts\Services\ReportServiceInterface $reportService
     * @param \App\Contracts\Services\SaleServiceInterface $saleService
     * @param \App\Contracts\Services\SaleReturnServiceInterface $saleReturnService
     * @param \App\Contracts\Services\DamageLossServiceInterface $damagelossService
     * @param \App\Contracts\Services\CompanyServiceInterface $companyService
     * @return void
     */
    public function __construct(ShopServiceInterface $shopService, CategoryServiceInterface $categoryService, WarehouseServiceInterface $warehouseService, ReportServiceInterface $reportService, ProductServiceInterface $productService, SaleServiceInterface $saleService, SaleReturnServiceInterface $saleReturnService, DamageLossServiceInterface $damagelossService, CompanyServiceInterface $companyService)
    {
        $this->shopService = $shopService;
        $this->categoryService = $categoryService;
        $this->warehouseService = $warehouseService;
        $this->reportService = $reportService;
        $this->productService = $productService;
        $this->saleService = $saleService;
        $this->saleReturnService = $saleReturnService;
        $this->damagelossService = $damagelossService;
        $this->companyService = $companyService;
    }
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //download excel report for best_selling_product, sales_by_product_category and damage&loss
        if (! is_null($request->download)) {
            if ($request->select_report == "best_selling_report") {
                return Excel::download(new BestSellingProductsExport($this->reportService, $request), 'bestsellingproducts.xlsx');
            }
            if ($request->select_report == "sales_by_product_category_report") {
                return Excel::download(new SalesByProductCategoryExport($this->reportService, $request), 'salesbyproductcategory.xlsx');
            }
            if ($request->select_report == "damage_and_loss_report") {
                return Excel::download(new DamageAndLossExport($this->reportService, $request), 'damageandloss.xlsx');
            }
        } else {
            //shoplist, warehouselist and categorylist
            $shopList = $this->shopService->getAllShopList();
            $warehouseList = $this->warehouseService->getAllWarehouseList();
            $categoryList = $this->categoryService->getProductCategoryList();
            // request report data
            $data = $this->reportService->getData($request);

            if (is_null($data)) {
                return back()->with('error_status', __('message.E0001'));
            }
            return view('report.index', compact('shopList', 'categoryList', 'data', 'warehouseList'));
        }
    }

    /**
     * Display a listing of sale.
     *
     * @param App\Http\Requests\SaleReportRequest $request
     * @return \Illuminate\Http\Response
     */
    public function saleReport(SaleReportRequest $request)
    {
        if (! is_null($request->download)) {
            return Excel::download(new SalesExport($this->saleService, $request), 'sales.xlsx');
        }
        $shopList = $this->shopService->getAllShopList();
        $data = $this->reportService->getSaleReportData($request);
        if (is_null($data)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('report.sale', compact('shopList', 'data'));
    }

    /**
     * Display a listing of sale category.
     *
     * @param App\Http\Requests\SaleCategoryReportRequest $request
     * @return \Illuminate\Http\Response
     */
    public function saleCategoryReport(SaleCategoryReportRequest $request)
    {
        if (! is_null($request->download)) {
            return Excel::download(new SaleCategoryExport($this->saleService, $request), 'salecategory.xlsx');
        }
        $shopList = $this->shopService->getAllShopList();
        $data = $this->reportService->getSaleCategoryReportData($request);

        if (is_null($data)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('report.salecategory', compact('shopList', 'data'));
    }

    /**
     * Display a listing of sale product.
     *
     * @param App\Http\Requests\SaleProductReportRequest $request
     * @return \Illuminate\Http\Response
     */
    public function saleProductReport(SaleProductReportRequest $request)
    {
        if (! is_null($request->download)) {
            return Excel::download(new SaleProductExport($this->saleService, $request), 'saleproduct.xlsx');
        }
        $shopList = $this->shopService->getAllShopList();
        $data = $this->reportService->getSaleProductReportData($request);
        if (is_null($data)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('report.saleproduct', compact('shopList', 'data'));
    }

    /**
     * Display a listing of top ten sale product.
     *
     * @param App\Http\Requests\TopSaleProductReportRequest $request
     * @return \Illuminate\Http\Response
     */
    public function topSaleProductReport(TopSaleProductReportRequest $request)
    {
        if (! is_null($request->download)) {
            return Excel::download(new TopSaleProductExport($this->saleService, $request), 'topsaleproduct.xlsx');
        }
        $shopList = $this->shopService->getAllShopList();
        $data = $this->reportService->getTopSaleProductReportData($request);
        if (is_null($data)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('report.topsaleproduct', compact('shopList', 'data'));
    }

    /**
     * Display a listing of the product.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function productReport(Request $request)
    {
        if (! is_null($request->download)) {
            return Excel::download(new ProductsExport($this->productService, $request), 'products.xlsx');
        }
        $shopList = $this->shopService->getAllShopList();
        $data = $this->reportService->getProductReportData($request);
        if (is_null($data)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('report.product', compact('shopList', 'data'));
    }

    /**
     * Display a listing of company license.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function companyLicenseReport(Request $request)
    {
        if (! is_null($request->download)) {
            return Excel::download(new CompanyLicenseExport($this->companyService, $request), 'companylicenses.xlsx');
        }
        $companyList = $this->companyService->getAllCompanyList();
        $data = $this->reportService->getCompanyLicenseReportData($request);
        if (is_null($data)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('report.companyLicense', compact('companyList', 'data'));
    }

    /**
     * Display a listing of the company.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response
     */
    public function companyReport(Request $request)
    {
        if (! is_null($request->download)) {
            return Excel::download(new CompanyExport($this->companyService, $request), 'company.xlsx');
        }
        $companyList = $this->companyService->getAllCompanyList();
        $data = $this->reportService->getCompanyReportData($request);
        if (is_null($data)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('report.company', compact('companyList', 'data'));
    }

    /**
     * Display a listing of invoice.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function invoiceReport(Request $request)
    {
        if (! is_null($request->download)) {
            return Excel::download(new InvoiceExport($this->saleService, $request), 'invoices.xlsx');
        }
        $shopList = $this->shopService->getAllShopList();
        $data = $this->reportService->getInvoiceReportData($request);

        if (is_null($data)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('report.invoice', compact('shopList', 'data'));
    }

    /**
     * Display a listing of invoice details.
     *
     * @param App\Http\Requests\InvoiceDetailsReportRequest $request
     * @return \Illuminate\Http\Response
     */
    public function invoiceDetailsReport(InvoiceDetailsReportRequest $request)
    {
        if (! is_null($request->download)) {
            return Excel::download(new InvoiceDetailsExport($this->saleService, $request), 'invoiceDetails.xlsx');
        }
        $data = $this->reportService->getInvoiceDetailsReportData($request);

        if (is_null($data)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('report.invoicedetails', compact('data'));
    }

    /**
     * Display a listing of sale return.
     *
     * @param App\Http\Requests\SaleReportRequest $request
     * @return \Illuminate\Http\Response
     */
    public function saleReturnReport(SaleReportRequest $request)
    {
        if (! is_null($request->download)) {
            return Excel::download(new SaleReturnExport($this->saleReturnService, $request), 'saleReturn.xlsx');
        }
        $shopList = $this->shopService->getAllShopList();
        $data = $this->reportService->getSaleReturnReportData($request);
        if (is_null($data)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('report.salereturn', compact('shopList', 'data'));
    }

    /**
     * Display a listing of damage&loss.
     *
     * @param App\Http\Requests\DamageLossReportRequest $request
     * @return \Illuminate\Http\Response
     */
    public function damageLossReport(DamageLossReportRequest $request)
    {
        if (! is_null($request->download)) {
            return Excel::download(new DamageAndLossExport($this->damagelossService, $request), 'damageloss.xlsx');
        }
        $shopList = $this->shopService->getAllShopList();
        $data = $this->reportService->getDamageLossReportData($request);

        if (is_null($data)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('report.damageloss', compact('shopList', 'data'));
    }

    /**
     * Display a listing of stock inventory product.
     *
     * @param App\Http\Requests\InventoryStockProductRequest $request
     * @return \Illuminate\Http\Response
     */
    public function stockInventoryProductReport(InventoryStockProductRequest $request)
    {
        if (! is_null($request->download)) {
            return Excel::download(new InventoryStockProductExport($this->productService, $request), 'inventoryproduct.xlsx');
        }
        $shopList = $this->shopService->getAllShopList();
        $data = $this->reportService->getInventoryStockProductReportData($request);
        if (is_null($data)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('report.inventorystockproduct', compact('shopList', 'data'));
    }

    /**
     * Display a listing of stock inventory category.
     *
     * @param App\Http\Requests\InventoryStockCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function stockInventoryCategoryReport(InventoryStockCategoryRequest $request)
    {
        if (! is_null($request->download)) {
            return Excel::download(new InventoryStockCategoryExport($this->categoryService, $request), 'inventorycategory.xlsx');
        }
        $shopList = $this->shopService->getAllShopList();
        $data = $this->reportService->getInventoryStockCategoryReportData($request);
        if (is_null($data)) {
            return back()->with('error_status', __('message.E0001'));
        }
        return view('report.inventorystockcategory', compact('shopList', 'data'));
    }
}
