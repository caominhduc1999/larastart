<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->paginate(5);
        return response()->json($categories, 200);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $request->validate([
           'name'   =>  'required',
           'image'   =>  'required|image|mimes:jpg,png,jpeg',
        ]);

        $category = new Category();
        $category->name = $request->name;

        $path = $request->file('image')->store('category_images');

        $category->image = $path;

        if ($category->save()){
            return response()->json($category, 200);
        }else{
            return response()->json([
                'message'   =>  'Some errors occured. Please try again ! !',
                'status_code'   =>  500
            ], 500);
        }
    }


    public function show(Category $category)
    {
        //
    }


    public function edit(Category $category)
    {
        //
    }


    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'   =>  'required',
        ]);


        $category->name = $request->name;

        $oldPath = $category->image;
        if ($request->hasFile('image')){
            $request->validate([
                'image'   =>  'image|mimes:jpg,png,jpeg',
            ]);
            $path = $request->file('image')->store('category_images');
            $category->image = $path;

            Storage::delete($oldPath);
        }

        if ($category->save()){
            return response()->json($category, 200);
        }else{
            Storage::delete($path);
            return response()->json([
               'message'    =>  'Some errors occured. Please try again !',
                'status_code'   =>  500
            ], 500);
        }
    }


    public function destroy(Category $category)
    {
        if($category->delete()){
            Storage::delete($category->image);
            return response()->json([
                'message'   =>  'Category deleted successfully !',
                'status_code'   =>  200
            ], 200);
        }else{
            return response()->json([
                'message'   =>  'Some errors occured. Please try again ! !',
                'status_code'   =>  500
            ], 500);
        }
    }
}
