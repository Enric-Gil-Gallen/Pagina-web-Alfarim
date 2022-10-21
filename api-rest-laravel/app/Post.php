<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';
    // Referencia a la base de datos
    protected $fillable = [
        'title', 'sub_category_id', 'measure', 'weight', 'price', 'image'
    ];
            
    
    // Relación Muchos a uno
    public function sub_category(){
        return $this->belongsTo('App\Sub_Category', 'sub_category_id');
    }
    
}
