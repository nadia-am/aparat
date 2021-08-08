<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    //region model config
    protected $table = 'categories';
    protected $fillable = [
        'title','icon','banner','user_id'
    ];
    //endregion

    //region user
    public function user()
    {
        return $this->belongsTo(Category::class);
    }
    //endregion
}
