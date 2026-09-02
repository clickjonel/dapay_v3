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
    public function values(){
        return $this->hasMany(ReportValue::class,'report_id','id');
    }

    public function users(){
        return $this->belongsToMany(
            User::class,
            'report_users',
            'report_id',
            'user_id'
        )->withPivot(['id']);
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id');
    }

    public function actionBy()
    {
        return $this->belongsTo(User::class, 'action_by');
    }

    public function pkActivity(){
        return $this->belongsTo(PKActivity::class);
    }

}
