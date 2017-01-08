<?php

namespace App\Http\Controllers;

use App\Like;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Session\Store;

class PostController extends Controller
{
    public function getIndex(Store $session)
    {
       //$posts = Post::all();
       //$posts = Post::orderBy('created_at', 'desc')->get();
       $posts = Post::orderBy('created_at', 'desc')->paginate(2);

        return view('blog.index', ['posts' => $posts]);
    }

    public function getAdminIndex()
    {
        //$posts = Post::all();
        $posts = Post::orderBy('title', 'asc')->get();
        return view('admin.index', ['posts' => $posts]);
    }

    public function getPost( $id)
    {
        // Getting single post
       //$post = Post::find($id);
       $post = Post::where('id' ,'=', $id)->first();
        return view('blog.post', ['post' => $post]);
    }


    public function getLikePost( $id)
    {
        $post = Post::where('id' ,'=', $id)->first();
        $like = new Like();
        $post->likes();
        $post->likes()->save($like);
        return redirect()->back();
    }

    public function getAdminCreate()
    {
        $tags = Tag::all();
//        return view('admin.create', compact('tags'));
        return view('admin.create', ['tags' => $tags]);
    }

    public function getAdminEdit($id)
    {
        // To find the specific Id  in a post
       $post = Post::find($id);
        $tags = Tag::all();
        return view('admin.edit', ['post' => $post, 'postId' => $id], compact('tags'));
    }

    public function postAdminCreate(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);
        $post = new Post([
            'title' => $request->input('title'),
            'content' => $request->input('content')
        ]);
        $post->save();
        $post->tags()->attach($request->input['tags'] === null ? [] : $request->input('tags'));

        //$post->addPost($session, $request->input('title'), $request->input('content'));
        return redirect()->route('admin.index')->with('info', 'Post created, Title is: ' . $request->input('title'));
    }

    public function postAdminUpdate(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);
         // Update the content and Title
        $post = Post::find($request->input('id'));
        $post ->title = $request->input('title');
        $post ->content = $request->input('content');
        $post->save();
//        $post->tags()->detach();
//        $post->tags()->attach($request->input['tags'] === null ? []: $request->input('tags'));

        $post->tags()->sync($request->input['tags'] === null ? []: $request->input('tags'));
        return redirect()->route('admin.index')->with('info', 'Post edited, new Title is: ' . $request->input('title'));


    }

    public function  getAdminDelete($id)
    {
      $post = Post::find($id);
      $post->likes()->delete();  // Delete the related likes
     $post->dettach();
      $post->delete();
        return redirect()->route('admin.index')->with('info', 'Post Deleted');
    }
}
