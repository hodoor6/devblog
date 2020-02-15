<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    const IS_ALLOW = 1;
    const IS_DISALLOW = 0;

    public function posts()
    {
        return $this->hasOne(Post::class,'id');
    }

    public function author()
    {
        return $this->hasOne(Post::class,'id');
    }

    // cтатус опубликованый коментарий
    public function allow() {
        $this->status = Comment::IS_ALLOW;
        $this->save();
    }

    // cтатус на модерировании коментариц
    public function disallow() {
        $this->status = Comment::IS_DISALLOW;
        $this->save();
    }

    // переключатель статуса комментрия на модерировании или на опубликованый
    public function toggleStatus($value)
    {
        if ($this->status = 0) {
            return $this->allow();
        }else
        {
            return  $this->disallow();
        }



    }

    public function remove()
    {
        $this->delete();
    }
    }
