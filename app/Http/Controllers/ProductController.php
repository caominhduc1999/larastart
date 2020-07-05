<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function categories()
    {
        $categories = Category::all();
        return response()->json($categories, 200);
    }

    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(5);
        return response()->json($products, 200);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $request->validate([
            'category_id'   =>  'required',
            'name'   =>  'required',
            'image'   =>  'required|image|mimes:jpg,png,jpeg',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->category_id = $request->category_id;

        $path = $request->file('image')->store('product_images');

        $product->image = $path;

        if ($product->save()){
            return response()->json($product, 200);
        }else{
            return response()->json([
                'message'   =>  'Some errors occured. Please try again ! !',
                'status_code'   =>  500
            ], 500);
        }
    }


    public function show(Product $product)
    {
        //
    }


    public function edit(Product $product)
    {
        //
    }


    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id'   =>  'required',
            'name'   =>  'required',
        ]);


        $product->name = $request->name;
        $product->category_id = $request->category_id;

        $oldPath = $product->image;
        if ($request->hasFile('image')){
            $request->validate([
                'image'   =>  'image|mimes:jpg,png,jpeg',
            ]);
            $path = $request->file('image')->store('product_images');
            $product->image = $path;

            Storage::delete($oldPath);
        }

        if ($product->save()){
            return response()->json($product, 200);
        }else{
            Storage::delete($path);
            return response()->json([
                'message'    =>  'Some errors occured. Please try again !',
                'status_code'   =>  500
            ], 500);
        }
    }


    public function destroy(Product $product)
    {
        if($product->delete()){
            Storage::delete($product->image);
            return response()->json([
                'message'   =>  'Product deleted successfully !',
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
