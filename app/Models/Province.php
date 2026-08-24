<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['psgc_code','name'])]
#[WithoutTimestamps]

class Province extends Model
{
    public function municipalities(){
        return $this->hasMany(Municipality::class);
    }
}
