<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table("pk_activities")]
#[Fillable([
    'date_start',
    'date_end',
    'activity_name',
    'type',
    'total_clients',
    'submitted_by',
])]
class PKActivity extends Model
{
    public function hrh(){
        return $this->belongsToMany(
            User::class,
            'pk_activity_hrh',
            'pk_activity_id',
            'user_id'
        )->withPivot(['id']);
    }

    public function barangays(){
        return $this->belongsToMany(
            Barangay::class,
            'pk_activity_barangays',
            'pk_activity_id',
            'barangay_id'
        )->withPivot(['id']);
    }

    public function programs(){
        return $this->belongsToMany(
            Program::class,
            'pk_activity_programs',
            'pk_activity_id',
            'program_id'
        )->withPivot(['id']);
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'pk_activity_id');
    }



}
