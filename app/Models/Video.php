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
        'published_at'
    ];
    //endregion

    //region relation
    public function playlist()
    {
        return $this->belongsToMany(Playlist::class,'playlist_videos')->first();
    }

    public function tags()
    {
        return  $this->belongsToMany(Tag::class ,'video_tags' );
    }
    //endregion relation
}
