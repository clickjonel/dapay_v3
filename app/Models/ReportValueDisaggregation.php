<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'report_value_id',
    'disaggregation_id',
    'value',
])]
class ReportValueDisaggregation extends Model
{
    public function disaggregation(){
        return $this->belongsTo(Disaggregation::class,'disaggregation_id','id');
    }
}
