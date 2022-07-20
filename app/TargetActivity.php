<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TargetActivity extends Model
{
    //
    protected $table = 'target_activity';
    protected $primaryKey = 'ID_TA';
    public $timestamps = false;
    public $fillable = [
        'ID_ACTIVITY',
        'ID_REGIONAL',
        'QUANTITY',
        'START_PP',
        'END_PP',
        'DELETED_AT'
    ];
    public function regional()
    {
        return $this->hasOne('App\Regional', 'ID_REGIONAL', 'ID_REGIONAL');
    }
    public function activity()
    {
        return $this->hasOne('App\ActivityCategory', 'ID_AC', 'ID_ACTIVITY');
    }
}