<?php

namespace App\Imports;

use App\Contracts\Services\CategoryServiceInterface;
use App\Contracts\Services\ProductServiceInterface;

use App\Http\Requests\ProductExcelImportRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    private $productService;
    private $categoryService;


    public function __construct(ProductServiceInterface $productService, CategoryServiceInterface $categoryService, ProductExcelImportRequest $request)
    {
        $this->productService   =   $productService;
        $this->categoryService  =   $categoryService;
        $this->request          =   $request;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
       
       //var_dump($row);die;
        if (! array_key_exists('productname', $row)) {
            throw new \Exception('Column name (productname) does not exist.');
        }
        if (! array_key_exists('category', $row)) {
            throw new \Exception('Column name (category) does not exist.');
        }
        if (! array_key_exists('mfddate', $row)) {
            throw new \Exception('Column name (mfddate) does not exist.');
        }
        if (! array_key_exists('expireddate', $row)) {
            throw new \Exception('Column name (expireddate) does not exist.');
        }
        if (! array_key_exists('description', $row)) {
            throw new \Exception('Column name (description) does not exist.');
        }
        if (! array_key_exists('saleprice', $row)) {
            throw new \Exception('Column name (saleprice) does not exist.');
        }
        if (! array_key_exists('minimumqty', $row)) {
            throw new \Exception('Column name (minimumqty) does not exist.');
        }
        if (! array_key_exists('barcode', $row)) {
            throw new \Exception('Column name (barcode) does not exist.');
        }
        if (! array_key_exists('barcodecheck', $row)) {
            throw new \Exception('Column name (barcodecheck) does not exist.');
        }

        // check date
        if (! empty($row['mfddate']) and ! empty($row['expireddate'])) {
            if (\Date("Y-m-d", strtotime($row['mfddate'])) >= \Date("Y-m-d", strtotime($row['expireddate']))) {
                throw new \Exception("ExpiredDate must be greater than MFDDate.");
            }
        }

        // check barcode
        if (empty($row['barcodecheck'])) {
            throw new \Exception('BarcodeCheck cant\'t be empty.');
        }
        if ($row['barcodecheck']!='generate' and $row['barcodecheck']!='productcode') {
            throw new \Exception("Barcodecheck value must be \"generate\" or \"productcode\".");
        }
        if ($row['barcodecheck']=='generate') {
            if (empty($row['barcode'])) {
                throw new \Exception('Barcode cant\'t be empty,when barcodecheck value is "generate".');
            }
        }
            
        
        // check sale price
        if (empty($row['saleprice'])) {
            throw new \Exception("Saleprice can't be empty.");
        }
        if (!is_int($row['saleprice'])) {
            throw new \Exception("Saleprice must be number.");
        }
        
        // check minimum qty
        if (empty($row['minimumqty'])) {
            throw new \Exception("MinimumQty can't be empty.");
        }
        if (!is_int($row['minimumqty'])) {
            throw new \Exception("MinimumQty must be number.");
        }
         
        
        $categoryName = $this->categoryService->getCategoryByName($row['category']);
        if (is_null($categoryName)) {
            throw new \Exception('Category column ('.$row['category'].') does not exist.');
        }

        $category     = $this->categoryService->getCategoryById($categoryName->id);
        if (is_null($category)) {
            throw new \Exception('Category data ('.$row['category'].') does not exist.');
        }
        $this->productService->insertMultiProduct($row, $category);
    }
}
