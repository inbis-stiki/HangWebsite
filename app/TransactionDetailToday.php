<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TransactionDetailToday extends Model
{
    protected $table = 'transaction_detail_today';
    // protected $primaryKey = 'ID_TDT';
    public $timestamps = false;

    public static function getData($idUser){
        return DB::select("
            SELECT 
                COALESCE((
                    SELECT SUM(tdt2.QTY_TD)
                    FROM transaction_detail_today tdt2
                    WHERE tdt2.ID_USER = tdt.ID_USER AND tdt2.ID_PC = 12
                ), 0) as UST,
                COALESCE((
                    SELECT SUM(tdt2.QTY_TD)
                    FROM transaction_detail_today tdt2
                    WHERE tdt2.ID_USER = tdt.ID_USER AND tdt2.ID_PC = 2
                ), 0) as NON_UST,
                COALESCE((
                    SELECT SUM(tdt2.QTY_TD)
                    FROM transaction_detail_today tdt2
                    WHERE tdt2.ID_USER = tdt.ID_USER AND tdt2.ID_PC = 3
                ), 0) as SELERAKU ,
                COALESCE((
                    SELECT SUM(tdt2.QTY_TD)
                    FROM transaction_detail_today tdt2
                    WHERE tdt2.ID_USER = tdt.ID_USER AND tdt2.ID_PC = 16
                ), 0) as RENDANG ,
                COALESCE((
                    SELECT SUM(tdt2.QTY_TD)
                    FROM transaction_detail_today tdt2
                    WHERE tdt2.ID_USER = tdt.ID_USER AND tdt2.ID_PC = 17
                ), 0) as GEPREK
            FROM transaction_detail_today tdt
            WHERE tdt.ID_USER = '".$idUser."'
            GROUP BY tdt.ID_USER 
        ");
    }
}
