<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;

#[Table("pk_activity_programs")]
#[Fillable([
    'pk_activity_id',
    'program_id',
])]
class PKActivityProgram extends Model
{
    //
}
