<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sub_Category extends Model
{
    // Referencia a la base de datos
    protected $table = 'sub_categories';
    
    // Unos a muchos
    public function give_posts(){
        return $this->hasMany('App\Post');
    }
    
    // Relación Muchos a uno
    public function category(){
        return $this->belongsTo('App\Category', 'category_id');
    }
}
