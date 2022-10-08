<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function getAllProducts()
    {
        $products = Product::all();

        return response()->json([
            'data' => $products,
            'total' => $products->count()
        ]);
    }

    public function getSingleProduct($id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return response()->json([
                'error' => 'Product not found'
            ]);
        }

        return response()->json([
            'data' => $product
        ]);
    }

    //public function searchProduct($id)
    
    public function searchProduct($title)
    {
        $products = Product::where('title', 'LIKE', '%'. $title. '%')->get();
        if($products)
        {
            return response()->json(['products'=>$products], 200);
        }
        else
        {
            return response()->json(['message'=>'No Product Found'], 404);
        }
    }

    public function getCategories()
    {
        $categories = Product::distinct('category')
            ->select('category')
            ->get();
        
        return response()->json([
            'data' => $categories
        ]);
    }

    public function getByCategory($name)
    {
        $products = Product::where('category', $name)->get();

        return response()->json([
            'data' => $products,
            'total' => $products->count()
        ]);
    }

    public function addProduct(Request $request)
    {
        try {
            $data = $request->json()->all();
            $products = new Product();
            $products->title = $data['title'];
            $products->price = $data['price'];
            $products->brand = $data['brand'];
            $products->category = $data['category'];
            $products->image = $data['image'];
            $products->save();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'error' => 'Unable to save product'
            ]);
        }
        
        return response()->json([
            'data' => $products
        ]);
    }

    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'title'=>'required',
            'description'=>'required',
            'currency'=>'required',
            'price'=>'required',
            'brand'=>'required',
            'category'=>'required',
            'image'=>'required'
        ]);

        $products = Product::find($id);
        $products->title = $request->title;
        $products->description = $request->description;
        $products->currency = $request->currency;
        $products->price = $request->price;
        $products->brand = $request->brand;
        $products->category = $request->category;
        $products->image = $request->image;
        $products->update();
        return response()->json(['message'=>'Product Updated Successfully'], 200);
    }

    public function deleteProduct(Request $request, $id)
    {
        $products = Product::find($id);
        $products->delete();
        return response()->json(['message'=>'Product Deleted Successfully'], 200);

    }


}