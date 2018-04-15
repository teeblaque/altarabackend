<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use Illuminate\Support\Str;
use App\Article;

class ArticleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        $blog = Article::inRandomOrder()->limit(10)->get();

        if (count($blog) > 0) {
            return response()->json(['status' => true, 'message' => 'success', 'data' => $blog]);
        } else {
            return response()->json(['status' => false, 'message' => 'no data found']);
        }    
    }

    public function adminart()
    {
        $blog = Article::all();

        if (count($blog) > 0) {
            return response()->json(['status' => true, 'message' => 'success', 'data' => $blog]);
        } else {
            return response()->json(['status' => false, 'message' => 'no data found']);
        }
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            // 'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $blog = new Article();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = Str::random(10).'.'.$image->getClientOriginalExtension();
            $location = 'images/'. $fileName;
            Image::make($image->getRealPath())->save($location);//resize(400, 200)->

            $blog->image = $fileName;

        }

        $blog->user_id = $request->user_id;
        $blog->title = $request->title;
        $blog->slug = strtolower(preg_replace('/\s+/', '-', $request->title));
        $blog->content = $request->content;

        if ($blog->save()) {

          return response()->json(['status' => true, 'message'=> 'success', 'data' => $blog]);

      } else {

       return response()->json(['status' => false, 'message' => 'blog not uploaded']);
   }
}

public function update(Request $request, $id)
{
    if ($id != null) {

        // $this->validate($request, [
        //     'user_id' => 'required',
        //     'title' => 'required|max:255',
        //     'content' => 'required',
        //     'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // ]);

        $blog = Article::findOrFail($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = Str::random(10).'.'.$image->getClientOriginalExtension();
            $location = 'images/'. $fileName;
            Image::make($image->getRealPath())->resize(300, 300)->save($location);

            $blog->image = $fileName;

        }

        $blog->user_id = $request->input('user_id');
        $blog->title = $request->input('title');
        $blog->slug = strtolower(preg_replace('/\s+/', '-', $request->input('title')));
        $blog->content = $request->input('content');

        if ($blog->save()) {

          return response()->json(['status' => true, 'message'=> 'updated success', 'data' => $blog]);

      } else {

       return response()->json(['status' => false, 'message' => 'blog not updated']);
   }

} else {
    return response()->json(['status' => false, 'message' => 'id cannot be null']);
}

}

public function singleArticle($slug)
{
    if ($slug != null) {

        $article = Article::where('slug', $slug)->get();
        if (count($article) > 0) {
            return response()->json(['status' => true, 'message' => 'success', 'data' => $article]);
        } else {
            return response()->json(['status' => false, 'message' => 'article not found']);
        }

    } else {
        return response()->json(['status' => false, 'message' => 'slug cannot be null']);
    }

}

public function singleById($id){
    if ($id != null) {
        $article = Article::where('id', $id)->get();
         if (count($article) > 0) {
            return response()->json(['status' => true, 'message' => 'success', 'data' => $article]);
        } else {
            return response()->json(['status' => false, 'message' => 'article not found']);
        }
        
    } else {
        return response()->json(['status' => false, 'message' => 'id cannot be null']);
    }
    
}

public function delete($id)
{
    if ($id != null) {

        $blog = Article::findOrFail($id);
        if ($blog->delete()) {
            return response()->json(['status' => true, 'message' => 'deleted successfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'failure']);
        }
        

    } else {
        return response()->json(['status' => false, 'message' => 'id cannot be null']);
    }
    
}
}
