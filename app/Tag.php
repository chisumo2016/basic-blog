<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //

    public function  posts()
    {
        return $this->belongsToMany('App\Post')->withTimestamps();
       // return $this->belongsToMany('App\Post', 'post_tag', 'post_id', 'tag_id'); u can use this as well


    }
}
