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

    public function barangays(){
        return $this->hasMany(Barangay::class);
    }

    public function reports(){
        return $this->hasManyThrough(Report::class, Barangay::class);
    }

    public function pkActivities()
    {
        return $this->hasManyThrough(
            PKActivity::class,         
            Barangay::class,            
            'province_id',             
            'id',                   
            'id',      
            'id'                
        );
    }

    public function teams(){
        return $this->hasManyThrough(
            Team::class,
            Barangay::class,
        );
    }
    
}
