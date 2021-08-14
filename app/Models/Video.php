<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    //region model config
    /**
     * In queue to proccessing
     */
    const STATE_PENDING = 'pending';
    /**
     * converted finished
     */
    const STATE_CONVERTED = 'converted';
    /**
     * video accepted add to queue
     */
    const STATE_ACCEPTED = 'accepted';
    /**
     * video not accepted
     */
    const STATE_BLOCKED = 'blocked';
    const STATE = [ self::STATE_PENDING ,self::STATE_CONVERTED ,self::STATE_ACCEPTED ,self::STATE_BLOCKED];

    protected $table = "videos";
    protected $fillable = [
        'user_id',
        'category_id',
        'channel_category_id',
        'slug',
        'title',
        'info',
        'duration',
        'banner',
        'enable_comments',
        'published_at',
        'state'
    ];

    public function getRouteKeyName()
    {
        return 'slug'; // TODO: Change the autogenerated stub
    }

    public function toArray()
    {
        $date = parent::toArray();
        $conditions = [
            'user_id'=> auth('api')->check()? auth()->id() : null,
            'video_id'=> $this->id,
        ];
        if (!auth('api')->check()){
            $conditions['user_ip'] = client_ip();
        }
        $date['liked'] = VideoFavourit::where($conditions)->count();
        return $date;
    }
    //endregion

    //region relation playlist
    public function playlist()
    {
        return $this->belongsToMany(Playlist::class,'playlist_videos')->first();
    }
    //endregion relation playlist

    //region relation tags
    public function tags()
    {
        return  $this->belongsToMany(Tag::class ,'video_tags' );
    }
    //endregion relation tags

    //region relation user
    public function user()
    {
        return  $this->belongsTo(User::class);
    }
    //endregion relation user

    //region check state
    public function isInState($state)
    {
        return $this->state == $state;
    }
    //endregion

    //region pendding
    public function isPendding()
    {
        return $this->isInState(self::STATE_PENDING);
    }
    //endregion

    //region accepted
    public function isAccepted()
    {
        return $this->isInState(self::STATE_ACCEPTED);
    }
    //endregion

    //region blocked
    public function isBlocked()
    {
        return $this->isInState(self::STATE_BLOCKED);
    }
    //endregion relation user

    //region converted
    public function isConverted()
    {
        return $this->isInState(self::STATE_CONVERTED);
    }
    //endregion relation user

    //region republished
    public static function whereRepublished()
    {
        return static::whereRaw('id in (select video_id from video_republishes)');
    }
    //endregion relation user

    //region notRepublished
    public static function whereNotRepublished()
    {
        return static::whereRaw('id not in (select video_id from video_republishes)');
    }
    //endregion relation user
}
