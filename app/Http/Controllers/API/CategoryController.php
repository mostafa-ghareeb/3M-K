<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index(){
        $categories = Category::all();
        return response()->json([
            'data' => $categories
        ]);
    }
    public function store(Request $request){
        $fields = $request->validate([
            'name'=>'required|string|unique:categories',
        ]);
        $category = new Category();
        $category->name = $fields['name'];
        $category->save();
        return response()->json([
            'data' => $category ,
            'message' => 'Category is saved.',
        ]);
    }
    public function update(Request $request , Category $category){
        $fields = $request->validate([
            'name'=>'required|string|unique:categories',
        ]);
        $category->name = $fields['name'];
        $category->save();
        return response()->json([
            'data' => $category ,
            'message' => 'Category is updated.',
        ]);
    }

    public function delete(Category $category){
        if($category){
            $category->delete();
            return response()->json([
                'data' => $category ,
                'message' => $category->name.' is deleted.',
            ]);
        }
    }
}
