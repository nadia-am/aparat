<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //region config
    use HasFactory;
    protected $table = "comments";
    protected $fillable = [	'user_id' ,	'video_id',	'parent_id' , 'body' , 'state'];

    const STATE_PENDING = 'pending';
    const STATE_accepted = 'accepted';
    const STATE_READ = 'read';
    const STATES = [
        self::STATE_PENDING,
        self::STATE_accepted,
        self::STATE_READ
    ];

    //endregion config

    //region relation
    public function video()
    {
        return $this->belongsTo(Video::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function parent()
    {
        return $this->belongsTo(Comment::class , 'parent_id');
    }
    //endregion relation

    //region static method
    protected static function channelComments($userId)
    {
        return Comment::join('videos','comments.video_id','=' , 'videos.id')
            ->selectRaw('comments.*')
            ->where('videos.user_id', '=' , $userId );
    }
    //endregion static method
}
