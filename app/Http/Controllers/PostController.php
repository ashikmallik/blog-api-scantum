<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->get();
     return response()->json(
            [
                'status' => 200,
                'message'=>'Successfully categories retrived',
                'data' => $posts,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'category_id' => 'required|integer',
            'title' => 'required|string|max:180|unique:posts',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
         return response()->json([
                'success' => false,
                'message' => 'Error',
                'errors' => $validator->getMessageBag(),
            ],422);
        }

        $data = $validator->validate();
        $data['slug'] = Str::slug($data['title']);

        if (array_key_exists('photo',$data)) {
            $data['photo'] = Storage::putFile('',$data['photo']);
        }

         Post::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Successfully post Created',
            'errors' => [],
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $posts = Post::find($id);

        if (!$posts) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
                'errors' =>[],
            ],422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully ',
            'data' => $posts,
        ],405);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::find($id);
        $validator = Validator::make($request->all(),[
            'category_id' => 'required|integer',
            'title' => 'required|string|max:180|unique:posts,title,'.$post->id,
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'content' => 'required|string',
        ]);

        

        if ($validator->fails()) {
         return response()->json([
                'success' => false,
                'message' => 'Error',
                'errors' => $validator->getMessageBag(),
            ],422);
        }

        $data = $validator->validate();
        $data['slug'] = Str::slug($data['title']);

        if (array_key_exists('photo',$data)) {
            Storage::delete($post->photo);
            $data['photo'] = Storage::putFile('',$data['photo']);
        }

         $post->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Successfully post Created',
            'success' => [],
        ],405);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'post not found',
                'errors' =>[],
            ],422);
        }
        Storage::delete($post->photo);
    $post->delete();
        return response()->json([
            'success' => true,
            'message' => 'Successfully deleted',
            'data' => [],
        ],405);
    }
}
