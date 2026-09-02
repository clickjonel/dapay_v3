<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['psgc_code','name','province_id'])]
#[WithoutTimestamps]

class Municipality extends Model
{
    public function province(){
        return $this->belongsTo(Province::class);
    }

    public function barangays(){
        return $this->hasMany(Barangay::class);
    }
}
