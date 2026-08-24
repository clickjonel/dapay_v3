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
    //
}
