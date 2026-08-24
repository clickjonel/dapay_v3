<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name','program_id','active','scope'])]
class ProgrammaticIndicator extends Model
{
    public function disaggregations(){
        return $this->belongsToMany(
            Disaggregation::class,
            'programmatic_indicator_disaggregations',
            'programmatic_indicator_id',
            'disaggregation_id'
        )->withPivot(['totalable','id']);
    }
}
