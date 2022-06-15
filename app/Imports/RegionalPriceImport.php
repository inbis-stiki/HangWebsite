<?php

namespace App\Imports;

use App\Product;
use App\Regional;
use App\RegionalPrice;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class RegionalPriceImport implements ToModel, WithStartRow
{
    private int $startRow = 9;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // dd($row);
        $product = Product::where('NAME_PRODUCT', '=', $row[1])->first();
        $regional = Regional::where('NAME_REGIONAL', '=', $row[3])->first();
        // $regional_id = $regional[0]['ID_REGIONAL'];
        $regionalprice = new RegionalPrice();
        $regionalprice->ID_PRODUCT      = 1;
        $regionalprice->ID_REGIONAL     = 1;
        $regionalprice->PRICE_PP        = 200000;
        $regionalprice->TARGET_PP       = 1;
        $regionalprice->START_PP        = date('Y-m-d');
        $regionalprice->END_PP        = date('Y-m-d');

        // dd($product->ID_PRODUCT);
        // $product_id = $product[0]['ID_PRODUCT'];
        return $regionalprice;
    }
    public function startRow(): int
    {
        return $this->startRow;
    }
}
