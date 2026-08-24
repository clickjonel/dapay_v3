<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name','active','pk_kit','eo_link','created_by','barangay_id'])]

class Team extends Model
{
    public function createdBy(){
        return $this->belongsTo(User::class,'created_by','id');
    }

    public function members(){
        return $this->hasMany(TeamMember::class);
    }

    public function barangay(){
        return $this->belongsTo(Barangay::class);
    }
}
