<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class User extends Authenticatable
{

    const IS_ADMIN = 1;
    const IS_USER = 0;
    const IS_BANNED = 1;
    const IS_ACTIVE = 0;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

//'password'
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }


//добавление пользователя
    public static function add($fields)
    {
//

        $user = new static;
        $user->fill($fields);
        $user->save();

        return $user;
    }

    //изменение пароля пользователя
    public function generatePassword($password)
    {


        if ($password != null) {
            $this->password = bcrypt($password);
            $this->save();
        }
    }


// редактирование пользователя
    public function edit($fields)
    {

//        dd($fields);
        $this->fill($fields);
        $this->save();
    }

    public function removeAvatar()
    {
        if ($this->avatar != null) {
            Storage::delete('uploads/' . $this->avatar);
//            $directory = 'uploads/' . $this->avatar;
//            File::delete($directory);
//    		unlink($directory);
        }
    }


//удаление пользователя
    public function remove()
    {
        $this->removeAvatar();
        $this->delete();
    }


//обновление Аватара
    public function uploadAvatar($image)
    {


        if ($image == null) {
            return;
        }
        $this->removeAvatar();

//        dd(get_class_methods($image));
//
        $filename = str_random(10) . '.' . $image->extension();
        $image->storeAs('uploads', $filename);
        $this->avatar = $filename;
        $this->save();

    }

    //вывод избражения для пользователя
    public function getImage()
    {

        if ($this->avatar == null) {
            return '/img/no-image.png';
        }
        return '/uploads/' . $this->avatar;
    }


// перевод  статиса пользователя в администраторы
    public function makeAdmin()
    {
        $this->is_admin = User::IS_ADMIN;
        $this->save();
    }

// перевод  статиса из администратора в пользователи
    public function makeNormal()
    {
        $this->is_admin = User::IS_USER;
        $this->save();
    }

    // переключатель статуса пользователя на пользователя или администратора
    public function toggleAdmin($value)
    {
        if ($value == null) {
            return $this->makeNormal();

        } else {
            return $this->makeAdmin();
        }


    }


    // забанить пользователя
    public function ban()
    {
        $this->status = User::IS_BANNED;
        $this->save();
    }

// разбанить пользователя
    public function unban()
    {
        $this->status = User::IS_ACTIVE;
        $this->save();
    }


    // переключает пользователей на в режим разбана или бана
    public function toggleBan($value)
    {
        if ($value == null) {
            return $this->unban();
        } else {
            return $this->ban();
        }

    }
}
