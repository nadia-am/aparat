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

    //region model config
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

    public function isAdmin()
    {
        return $this->type == self::TYPES_ADMIN ;
    }
    public function isNormalUser()
    {
        return $this->type == self::TYPES_USER ;
    }
    //endregion

    //region model channel
    public function channel()
    {
        return $this->hasOne(Channel::class);
    }
    //endregion

    //region model categories
    public function categories()
    {
        return $this->hasMany(Category::class);
    }
    //endregion

    //region model playlists
    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }
    //endregion

    public function findForPassport($username)
    {
        $user = static::where('mobile',$username)->orWhere('email',$username)->first();
        return $user;
    }

    //region getter mobile
    public function setMobileAttribute($value)
    {
        $mobile = to_valid_mobile_number($value);
        $this->attributes['mobile'] = $mobile;
    }
    //endregion


}


