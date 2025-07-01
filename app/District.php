<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'md_district';
    protected $primaryKey = 'ID_DISTRICT';
    public $timestamps = false;

    public function area(){
        return $this->hasOne('App\Area', 'ID_AREA', 'ID_AREA');
    }
}
