<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;

class Post extends Model
{

    protected $dates = ['date'];
//    protected $dateFormat = 'Y-m-d';
    use Sluggable;

    protected $fillable = [
        'title', 'content', 'date','description',
    ];


//преобразование дати в формат Y-m-d
    public function setDateAttribute($value)
    {
        $date = Carbon::createFromFormat('d/m/y', $value)->format('Y-m-d');
        $this->attributes['date'] = $date;
    }


//преобразование дати в формат d/m/Y
    public function getDateAttribute($value)
    {
        $date = Carbon::createFromFormat('Y-m-d', $value)->format('d/m/y');
        return $this->attributes['date'] = $date;
    }

    const IS_DRAFT = 1;
    const IS_PUBLIC = 0;
    const IS_FEATURED = 1;
    const IS_STANDART = 0;


// связь с категориями
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //связь с пользователями
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    // мое добавление статьи
    public static function addPost($fillable)
    {
        $post = new static;
        $post->fill($fillable);
        $post->user_id = 1;
        $post->save();

        return $post;
    }

    //редактирование статьи
    public function editPost($fillable)
    {
        $this->fill($fillable);
        $this->save();

    }

//удаление статьи
    public function remove()
    {
//удаление кортинки со статьи
        $this->removeImage();
        $this->tags()->detach();
        $this->delete();
    }

    //создание и обновление изображение
    public function uploadImage($image)
    {
        if ($image == null) { return; }
        Storage::delete('uploads/' . $this->image);
        $filename = str_random(10) . '.' . $image->extension();
        $image->saveAs('uploads', $filename);
        $this->image = $filename;
        $this->save();

    }

//добавление категории или обновление категории
    public function setCategory($idCategory)
    {
        if ($idCategory == null) {
            return;
        }
        $this->category_id = $idCategory;
        $this->save();
    }
//вывод заголовков категорий на странице статей
    public function getCategoryTitle()
    {

        return ($this->category != null)
            ? $this->category->title
            : 'Нет категории';

    }

//вывод тегов на cтранице статей
    public function getTagsTatles()
    {

        return (!$this->tags->isEmpty())
            ? implode(', ', $this->tags->pluck('title')->all())
            : 'Теги не добавлены для этой категории';
//        $tags = '';
////       foreach ($this->tags as $tag) {
////            $tags .= $tag->title . ', ';
//        }
////        return rtrim($tags, ', ');
    }
//Удаление изображение
    public function removeImage()
    {
        if ($this->image != null) {
            Storage::delete('posts/' . $this->image);

        }
    }

//Добавление  тегов при создании
    public function setTags($ids)
    {
        if ($ids == null) {
            return;
        }
        $this->tags()->sync($ids);
    }

// перевод страницы в статус опубликованные
    public function setPublic()
    {

        $this->status = Post::IS_PUBLIC;
        $this->save();
    }

    // переключатель статуса статьи на опубликованный или черновик
    public function setDraft()
    {
        $this->status = Post::IS_DRAFT;
        $this->save();
    }

//переключатель черновик или опубликованный
    public function toggleStatus($status)
    {
        if ($status == null) {
            return $this->setPublic();
        }
        else
            {
            return $this->setDraft();
        }
    }

    // переключает статью в избранное
    public function setFeatured()
    {
        $this->is_featured = Post::IS_FEATURED;
        $this->save();
    }

    // переключает статью из избранного в обычное состояние
    public function setStandart()
    {
        $this->is_featured = Post::IS_STANDART;
        $this->save();
    }

//переключатель черновик или опубликованный
    public function toggleFeatured($featured)
    {
        if ($featured == null) {
            return $this->setStandart();
        }
        else
            {
            return $this->setFeatured();
        }

    }


//изображение по умолчанию или загруженное
    public function getImage()
    {
//       dd(public_path().DIRECTORY_SEPARATOR .'img/no-image.png');
//        dd(public_path('/img/no-image.png'));
//        img/no-image.png
        //       return   $img = Storage::url('images/index/no-image.png');
        if ($this->image == null) {
//            return asset('img/no-image.png');
            return '/img/no-image.png';
        }
        return '/posts/' . $this->image;
    }

    //обновление тегов через detach() и attach

    public function updateTags($arrayTags)
    {
        if ($arrayTags == null) {
            return $arrayTags;
        }
        $this->tags()->detach();
        $this->tags()->attach($arrayTags);
    }

    public function getCategoryID()

    {
     return ($this->category_id != null)
         ? $this->category_id
        : null;
    }


    public function getDate()
    {
         $date = Carbon::createFromFormat('d/m/y', $this->date)->format('F d,Y');

        return $date;
    }


}


