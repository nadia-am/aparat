<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory,SoftDeletes;

    //region model config
    protected $table = 'tags';
    protected $fillable = ['title'];
    //endregion

    //region videos
    public function videos()
    {
        return  $this->belongsToMany(Video::class ,'video_tags' );
    }
    //endregion

    //region model methods overwritted
    public function toArray()
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
        ];

    }
    //endregion

}
