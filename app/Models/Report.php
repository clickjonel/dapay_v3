<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'date',
    'total_clients',
    'total_returning_clients',
    'barangay_id',
    'remarks',
    'status',
    'action_by',
    'pk_activity_id'
])]
class Report extends Model
{
    
}
