<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{


    use Sluggable;
    protected $fillable = ['title', 'content'];

    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;
    const IS_FEATURED = 1;
    const IS_STANDART = 0;

    public function category()
    {
        return $this->hasOne(Category::class);
    }

    public function author()
    {
        return $this->hasOne(User::class);
    }




//cвязь с промежуточной таблицей
    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'post_tags',
            'post_id',
            'tag_id'
        );
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function add($fields)
    {
        $post = new static;
        $post->fill($fields);
        $post->user_id = 1;
        $post->save();

        return $post;
    }

    //редактиование статьи
    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    public function remove()
    {
        //удалить картинку поста
        Storage::delete('uploads/' . $this->image);
        // удалить пост
        $this->delete();
    }

//обновление избражения
    public function uploadImage($image)
    {
        if ($image == null) { return; }
        Storage::delete('uploads/' . $this->image);
        $filename = str_random(10) . '.' . $image->extension();
        $image->saveAs('uploads', $filename);
        $this->image = $filename;
        $this->save();

    }

//присваения категории
    public function setCategory($id)
    {
        if ($id == null) {
            return;
        }
        $this->category_id = $id;
        $this->save();
    }

    //сохранение тегов и обновление тегов
    public function setTag($ids)
    {
        if ($ids == null) {
            return;
        }
        $this->tags()->sync($ids);
    }

// перевод  статиса страници в черновики
    public function setDraft()
    {
        $this->status = Post::IS_DRAFT;
        $this->save();
    }

// перевод страници в статус опубликованые
    public function setPublic()
    {
        $this->status = Post::IS_PUBLIC;
        $this->save();
    }

    // переключатель статуса статьи на опубликованый или черновик
    public function toggleStatus($value)
    {
        if ($value == null) {
          return  $this->setDraft();
        }else
        {
           return $this->setPublic();
        }


    }


    // пеключает статью в избраное
    public function setFeatured()
    {
        $this->is_featured = Post::IS_FEATURED;
        $this->save();
    }

// пеключает статью в  не избраное
    public function setStandart()
    {
        $this->is_featured = Post::IS_STANDART;
        $this->save();
    }


    // переключает статью на в режим избраный или обычный
    public function toggleFeatured($value)
    {
        if ($value == null) {
            return  $this->setStandart();
        }
        else
        {
            return $this->setFeatured();
        }

    }


    //вывод избражения для поста
    public function getImage()
    {
      if($this->image == null)
      {
        return '/img/no-image.png';
      }
      return '/uploads/' . $this->image;
    }

}


