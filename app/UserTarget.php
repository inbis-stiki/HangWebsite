<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserTarget extends Model
{
    protected $table = 'user_target';
    protected $primaryKey = 'ID_UT';
    public $timestamps = false;

    public $fillable = [
        'ID_USER',
        'TOTALACTIVITY_UT',
        'TOTALSALES_UT'
    ];

    public static function queryGetTargetSaleCategory($totArea, $idRegional){
        return DB::select("
            SELECT 
                mpc.NAME_PC ,
                SUM(ts.QUANTITY) / (".$totArea." *3) / 25 as TOTAL
            FROM target_sale ts , md_product mp , md_product_category mpc 
            WHERE 
                ts.ID_REGIONAL = '".$idRegional."'
                AND ts.ID_PRODUCT = mp.ID_PRODUCT 
                AND mp.ID_PC = mpc.ID_PC 
            GROUP BY mpc.ID_PC 
            ORDER BY mpc.NAME_PC DESC
        ");
    }
}
