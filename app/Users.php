<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Users extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'ID_USER';
    protected $hidden = array('PASS_USER');
    public $incrementing = false;
    public $timestamps = false;

    public static function getUserByRegional($idRegional, $idUsers){
        return DB::select("
            SELECT u.ID_USER, u.NAME_USER, ma.NAME_AREA, mr.NAME_ROLE
            FROM `user` u 
            INNER JOIN md_area ma
                ON u.deleted_at IS NULL AND ma.ID_AREA = u.ID_AREA
            INNER JOIN md_role mr
                ON mr.ID_ROLE = u.ID_ROLE
            WHERE u.ID_REGIONAL = '".$idRegional."' AND u.ID_ROLE IN (5, 6) AND u.ID_USER NOT IN (".$idUsers.")
            ORDER BY ma.NAME_AREA ASC, u.NAME_USER ASC
        ");
    }
}
