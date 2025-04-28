<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;
use Illuminate\Http\Request;
class PostController extends Controller{
    /**
     * index
     *
     * @return View
     */

     public function index(): View{
        $posts = Post::latest()->paginate(5);
        return view('posts.index', compact('posts'));
     }
}