<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use HasFactory,SoftDeletes;

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
    //endregion

    //region Overwrite
    public function getRouteKeyName()
    {
        return 'slug'; // TODO: Change the autogenerated stub
    }
    public function toArray()
    {
        $date = parent::toArray();

        $date['video_link'] = $this->video_link;
        $date['banner_link'] = $this->banner_link;
        $date['views'] = VideoView::where(['video_id'=> $this->id])->count();

        return $date;
    }
    //endregion Overwrite

    //region getter
    public function getVideoLinkAttribute()
    {
        return Storage::disk('videos')
            ->url( $this->user_id . '/' . $this->slug . '.mp4' );
    }
    public function getBannerLinkAttribute()
    {
        return Storage::disk('videos')
            ->url( $this->user_id . '/' . $this->slug . '-banner' );
    }
    //endregion getter

    //region relations
    public function playlist()
    {
        return $this->belongsToMany(Playlist::class,'playlist_videos');
    }
    public function tags()
    {
        return  $this->belongsToMany(Tag::class ,'video_tags' );
    }
    public function user()
    {
        return  $this->belongsTo(User::class);
    }
    public function viewers()
    {
        //TODO add user which did not log in
        return $this->belongsToMany(User::class,'video_views')->withTimestamps();
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public static function channelComments($userId)
    {
        return static::where('videos.user_id',$userId)
            ->join('comments','videos.id','=','comments.video_id');
    }
    public function related()
    {
        return static::selectRaw( 'COUNT(*) related_tags, videos.*' )
            ->leftJoin('video_tags','videos.id','=','video_tags.video_id')
            ->whereRaw('videos.id !=' . $this->id)
            ->whereRaw("videos.state = '". self::STATE_ACCEPTED . " ' ")
            ->whereIn(DB::raw('video_tags.tag_id') , function ($query){
                $query->selectRaw('video_tags.tag_id')
                    ->from('videos')
                    ->leftJoin('video_tags','videos.id','=','video_tags.video_id')
                    ->whereRaw('videos.id =' . $this->id);
            })
            ->groupBy(DB::raw('videos.id'))
            ->orderBy('related_tags','desc');


    }
    //endregion relations

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

    //region Custom Static method
    public static function whereRepublished()
    {
        return static::whereRaw('id in (select video_id from video_republishes)');
    }

    public static function whereNotRepublished()
    {
        return static::whereRaw('id not in (select video_id from video_republishes)');
    }

    /**
     * get my videos views
     * @param $userId
     * @return Builder
     */
    public static function views($userId)
    {
        return static::where('videos.user_id',$userId)
            ->join('video_views','videos.id','=','video_views.video_id');
    }
    //endregion Custom Static method


}
