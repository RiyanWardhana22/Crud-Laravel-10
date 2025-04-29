<?php
namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller{
   // METHOD INDEX
     public function index(): View{
        $posts = Post::latest()->paginate(5);
        return view('posts.index', compact('posts'));
     }

   // METHOD CREATE
     public function create(): View{
      return view('posts.create');
     }

   // METHOD STORE 
     public function store(Request $request): RedirectResponse{
      // Validasi Form
      $this->validate($request, [
         'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
         'title' => 'required|min:5',
         'content' => 'required|min:10'
      ]);

      // Upload Image
      $image = $request->file('image');
      $image->storeAs('public/posts', $image->hashName());

      // Create Post
      Post::create([
         'image' => $image->hashName(),
         'title' => $request->title,
         'content' => $request->content
      ]);

      return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Disimpan!']);
     }

   // Method Show
   public function show(string $id): view{
      $post = Post::findOrFail($id);
      return view('posts.show', compact('post'));
   }

   // METHOD EDIT
   public function edit(string $id): view{
      $post = Post::findOrFail($id);
      return view('posts.edit', compact('post'));
   }

   public function update(Request $request, $id): RedirectResponse{
      $this->validate($request, [
         'image' => 'image|mimes:jpeg,png,jpg|max:2048',
         'title' => 'required|min:5',
         'content' => 'required|min:10'
      ]);

      $post = Post::findOrFail($id);
      if ($request->hasFile('image')){
         $image = $request->file('image');
         $image->storeAs('public/posts', $image->hashName());
         Storage::delete('public/posts/'.$post->image);
         $post->update([
            'image' => $image->hashName(),
            'title' => $request->title,
            'content' => $request->content
         ]);
      } else{
         $post->update([
            'title' => $request->title,
            'content' => $request->content
         ]);
      }
      return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Diupdate!']);
   }
}