<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Cartegory;
use App\Sub_Category;

class pruebas extends Controller
{
    public function testOrm() {
        
        $posts = Post::all();
        foreach ($posts as $post){
            echo "<span>{$post->sub_category->name}</span>";
            echo "<h1>".$post->title."</h1>";
            echo "<p>".$post->price."</p>";
            echo "<hr>";
        }
         
         
    }
}
