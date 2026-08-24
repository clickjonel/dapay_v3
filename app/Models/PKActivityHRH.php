<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;

#[Table("pk_activity_hrh")]
#[Fillable([
    'pk_activity_id',
    'user_id',
])]

class PKActivityHRH extends Model
{
    
}
