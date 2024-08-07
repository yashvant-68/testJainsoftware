<?php 
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::all();
            return view('post.index', compact('posts'));
        } catch (Exception $e) {
            return redirect('dashboard')->with('error', 'Failed to retrieve posts.');
        }
    }

    public function create()
    {
        try {
            return view('post.create');
        } catch (Exception $e) {
            return redirect('dashboard')->with('error', 'Failed to load the create post form.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        try {
              $post = new Post();
              $post->title = $request->title;
              $post->description = $request->description;
              $post->user_id = Auth::id();
              $post->save();

            return redirect('dashboard')->with('success', 'Post created successfully.');
        } catch (Exception $e) {
            return redirect('dashboard')->with('error', 'Failed to create post.');
        }
    }

    public function edit($id)
    {
        try {
            $post = Post::findOrFail($id);
            return view('post.edit', compact('post'));
        } catch (ModelNotFoundException $e) {
            return redirect('dashboard')->with('error', 'Post not found.');
        } catch (Exception $e) {
            return redirect('dashboard')->with('error', 'Failed to load the edit post form.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        try {
            $post = Post::findOrFail($id);
            $post->update($request->all());
            return redirect('dashboard')->with('success', 'Post updated successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect('dashboard')->with('error', 'Post not found.');
        } catch (Exception $e) {
            return redirect('dashboard')->with('error', 'Failed to update post.');
        }
    }

    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);
            $post->delete();
            return redirect('dashboard')->with('success', 'Post deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return redirect('dashboard')->with('error', 'Post not found.');
        } catch (Exception $e) {
            return redirect('dashboard')->with('error', 'Failed to delete post.');
        }
    }
}
