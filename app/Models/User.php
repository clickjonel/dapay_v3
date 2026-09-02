<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'password','province_id','access_level'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function province(){
        return $this->belongsTo(Province::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    protected $appends = [
        'is_online', 
    ];

     /**
     * Whether the user has an active session within the last 5 minutes.
     */
     protected function isOnline(): Attribute
     {
         return Attribute::make(
             get: fn () => DB::table('sessions')
                 ->where('user_id', $this->id)
                 ->where('last_activity', '>=', now()->subMinutes(5)->timestamp)
                 ->exists(),
         );
     }

     public function handledBarangays(){
        return $this->belongsToMany(
            Barangay::class,
            'user_handled_barangays',
            'user_id',
            'barangay_id'
        );
     }

     public function handledMunicipalities(){
        return $this->belongsToMany(
            Municipality::class,
            'user_handled_municipalities',
            'user_id',
            'municipality_id'
        );
     }

     public function reports(){
        return $this->belongsToMany(
            Report::class, 
            'report_users', 
            'user_id', 
            'report_id'
        );
     }

}
