<?php

namespace App\Models;

use App\Http\Requests\user\ChangeEmailRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable ,HasApiTokens;

    const TYPES_ADMIN = 'admin';
    const TYPES_USER = 'user';
    const TYPES = ['admin','user'];
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'mobile',
        'email',
        'name',
        'password',
        'avatar',
        'website',
        'verify_code',
        'verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'verify_code',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function channel()
    {
        return $this->hasOne(Channel::class);
    }

    public function findForPassport($username)
    {
        $user = static::where('mobile',$username)->orWhere('email',$username)->first();
        return $user;
    }

    public function setMobileAttribute($value)
    {
        $mobile = to_valid_mobile_number($value);
        $this->attributes['mobile'] = $mobile;
    }


}


