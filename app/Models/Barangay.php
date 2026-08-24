<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    // details
    'psgc_code',
    'name',
    'province_id',
    'municipality_id',
    'mov_link',

    // PK profile
    'pk_status',
    'pk_site',

    // geography
    'latitude',
    'longitude',
    'is_gida',

    // populations
    'total_population',
    'target_population',
    'total_puroks',
    'target_puroks',
    'total_households',
    'target_households',
])]
class Barangay extends Model
{
    public function province(){
        return $this->belongsTo(Province::class);
    }

    public function municipality(){
        return $this->belongsTo(Municipality::class);
    }

    public function organizationalIndicators(){
        return $this->belongsToMany(
            OrganizationalIndicator::class,
            'barangay_organizational_indicators',
            'barangay_id',
            'organizational_indicator_id'
        )->withPivot(['id','total','community_based']);
    }

    public function priorityPrograms(){
        return $this->belongsToMany(
            Program::class,
            'barangay_priority_programs',
            'barangay_id',
            'program_id'
        )->withPivot(['id','target','order']);
    }
}
