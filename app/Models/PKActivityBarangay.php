<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table("pk_activity_barangays")]
#[Fillable([
    'pk_activity_id',
    'barangay_id',
    'host',
])]
class PKActivityBarangay extends Model
{
    //
}
