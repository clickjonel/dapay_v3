<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['team_id','name','role','position','pk_oriented'])]
class TeamMember extends Model
{
    //
}
