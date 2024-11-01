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

    public static function getUserByRegional($idRegional, $idUsers)
    {
        return DB::select("
            SELECT
                u.ID_USER,
                u.NAME_USER,
                ma.NAME_AREA,
                mr.NAME_ROLE,
                t.LATEST_DATE_TRANS
            FROM
                `user` u
            INNER JOIN md_area ma ON
                u.deleted_at IS NULL
                AND ma.ID_AREA = u.ID_AREA
            INNER JOIN md_role mr ON
                mr.ID_ROLE = u.ID_ROLE
            LEFT JOIN (
                SELECT
                    ID_USER,
                    MAX(DATE_TRANS) AS LATEST_DATE_TRANS
                FROM
                    transaction
                GROUP BY
                    ID_USER
            ) t ON t.ID_USER = u.ID_USER
            WHERE
                ma.ID_REGIONAL = " . $idRegional . "
                AND u.ID_ROLE IN (5, 6)
                AND u.ID_USER NOT IN (" . $idUsers . ")
            ORDER BY
                ma.NAME_AREA ASC,
                u.NAME_USER ASC
        ");
    }
}
