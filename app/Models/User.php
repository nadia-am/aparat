<?php

namespace App\Models;

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

    //region model videos
    public function videos()
    {
        return $this->channelVideos()
            ->union(
                $this->republishVideos()
            );

    }
    //endregion

    //region model favourit videos
    public function favouritVideos()
    {
        return $this->hasManyThrough(
            Video::class,
            VideoFavourit::class,
            'user_id', //VideoFavourit.user_id
            'id', //video.id
            'id' ,//user.id
            'video_id');
    }
    //endregion

    //region model channel videos
    public function channelVideos()
    {
        return $this->hasMany(Video::class)->selectRaw('* , false as republish');
    }
    //endregion

    //region model republishVideos
    public function republishVideos()
    {
        return $this->hasManyThrough(
            Video::class,
            VideoRepublish::class,
        'user_id', //repblish.user_id
            'id', //video.id
            'id' ,//user.id
            'video_id')->selectRaw('videos.* , true as republish');//repblish.video_id
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


    public function followings( )
    {
        return $this->hasManyThrough(
            User::class,
            UserFollowing::class,
            'user_id1',
            'id',
            'id',
        'user_id2');
    }

    public function followers( )
    {
        return $this->hasManyThrough(
            User::class,
            UserFollowing::class,
            'user_id2',
            'id',
            'id',
            'user_id1');
    }

    public function follow(User $user)
    {
        return UserFollowing::create([
            'user_id1' => $this->id,
            'user_id2' => $user->id
        ]);

    }

    public function unfollow(User $user)
    {
        return UserFollowing::where([
            'user_id1' => $this->id,
            'user_id2' => $user->id
        ])->delete();

    }

    public function views()
    {
        return $this->belongsToMany(Video::class,'video_views')->withTimestamps();
    }
}


