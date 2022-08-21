<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRankingSale extends Model
{
    protected $table = 'user_ranking_sale';
    protected $primaryKey = 'ID_USER_RANKSALE';
    public $timestamps = false;
}
