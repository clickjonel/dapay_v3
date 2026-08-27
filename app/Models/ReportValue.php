<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'report_id',
    'indicator_id',
    'program_id',
    'total',
])]
class ReportValue extends Model
{
    public function disaggregations(){
        return $this->hasMany(ReportValueDisaggregation::class,'report_value_id','id');
    }

    public function indicator(){
        return $this->belongsTo(ProgrammaticIndicator::class,'indicator_id','id');
    }
}
