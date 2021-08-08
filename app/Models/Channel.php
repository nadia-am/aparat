<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    //region model config
    protected $table='channels';
    protected $fillable =[
        'user_id','name','info','banner','social'
    ];
    //endregion

    //region user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //endregion

    //region set social
    public function setSocialAttribute($value)
    {
        if (is_array($value)) $value = json_encode($value);
        $this->attributes['social'] = $value;
    }
    //endregion

    //region get social
    public function getSocialAttribute()
    {
        return json_decode($this->attributes['social'],true);
    }
    //endregion
}
