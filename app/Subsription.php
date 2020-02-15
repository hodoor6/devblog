<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subsription extends Model
{

    protected $fillable = ['email'];
// добавление подписки
    public static function add($email)
    {
        $sub = new static;
        $sub->email = $email;
        $sub->token = str_random(100);
        $sub->save();

        return $sub;
    }
 // удаления подписки
    public function remove () {
        $this->delete();
    }
}
