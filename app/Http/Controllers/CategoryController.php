<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    public function index(){

        $categories = Category::latest()->get();
     return response()->json(
            [
                'status' => 200,
                'message'=>'Successfully categories retrived',
                'data' => $categories,
            ]
        );
    }

    public function store(Request $request){
    
        $data = Validator::make($request->all(),[
            'name' => 'required|string|unique:Categories',
        ]);

        if ($data->fails()) {
         return response()->json([
                'success' => false,
                'message' => 'Error',
                'errors' => $data->getMessageBag(),
            ],422);
        }

        $formData = $data->validate();
        $formData['slug'] = Str::slug($formData['name']);

        $category = Category::create($formData);

        return response()->json([
            'success' => true,
            'message' => 'Successfully Category Created',
            'errors' => $category,
        ],405);
    }

    public function show($id){
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
                'errors' =>[],
            ],422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully ',
            'data' => $category,
        ],405);
    }

    public function update(Request $request, $id){

        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
                'errors' =>[],
            ],422);
        }


        $data = Validator::make($request->all(),[
            'name' => 'required|string|unique:Categories,name,'.$category->id,
        ]);

        if ($data->fails()) {
         return response()->json([
                'success' => false,
                'message' => 'Error',
                'errors' => $data->getMessageBag(),
            ],422);
        }

        $formData = $data->validate();
        $formData['slug'] = Str::slug($formData['name']);

        $category->update($formData);

        return response()->json([
            'success' => true,
            'message' => 'Successfully Category Updated',
            'errors' => [],
        ],405);

    }

    public function delete($id){
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found',
                'errors' =>[],
            ],422);
        }
    $category->delete();
        return response()->json([
            'success' => true,
            'message' => 'Successfully deleted',
            'data' => [],
        ],405);
    }

}
