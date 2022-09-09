<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRankingActivity extends Model
{
    protected $table = 'user_ranking_activity';
    protected $primaryKey = 'ID_USER_RANKACTIVITY';
    public $timestamps = false;
}
