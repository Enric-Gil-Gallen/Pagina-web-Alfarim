<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Referencia a la base de datos
    protected $table = 'categories';
    
    // Unos a muchos
    public function give_sub_categories(){
        return $this->hasMany('App\Sub_Caterory');
    }
    // Relación Muchos a uno
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
