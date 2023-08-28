<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(){

        $products = Product::all();

        if($products->count() > 0){
            return response()->json([
                'status'=>200,
                'products'=> $products,
            ],200);
        }else{
            return response()->json([
                'status'=> 404,
                'message'=> 'No Product Found'
            ],404);
        }

    }

    public function store(Request $request){
        $validatore = Validator::make($request->all(),[
            'name'=> 'required|min:3',
            'description'=> 'required|max:255',
            'price'=> 'required',
            'image'=> 'required',
            'category_id'=> 'required'
        ]);

        if($validatore){

            $slug = Str::slug($request->name);

            Product::create([
                'name'=>$request->name,
                'slug'=> $slug,
                'description'=>$request->description,
                'price'=>$request->price,
                'image'=> $request->image,
                'category_id'=> $request->category_id
            ]);
            return response()->json([
                'status'=> 200,
                'message'=> 'Product Created Successfully'
            ],200);
        }else{
            return response()->json([
                'status'=>400,
                'message'=>$validatore->messages()
            ],400);
        }
    }

    public function show($id){

        $product = Product::find($id);

        if(!$product){

            return response()->json([
                'status'=>404,
                'message'=> 'Product Not found'
            ],404);

        }else{

            return response()->json([
                'status'=> 200,
                'Product'=> $product
            ],200);
        }
    }

    public function update(Request $request,int $id){
        
        $product = Product::find($id);
        if(!$product){
            return response()->json([
                'status' => 404,
                'message'=> 'Product Not Found'
            ],404);
        }else{
                
            $validatore = Validator::make($request->all(),[
                'name'=> 'required|min:3',
                'description'=> 'required|max:255',
                'price'=> 'required',
                'image'=> 'required',
                'category_id'=> 'required'
            ]);

            if($validatore){

                $slug = Str::slug($request->name);
                $product->update([
                    'name'=>$request->name,
                    'slug'=> $slug,
                    'description'=>$request->description,
                    'price'=>$request->price,
                    'image'=> $request->image,
                    'category_id'=> $request->category_id
                ]);
                return response()->json([
                    'status'=> 200,
                    'message'=> 'Product Updated Successfully'
                ],200);
            }else{
                return response()->json([
                    'status'=>400,
                    'message'=>$validatore->messages()
                ],400);
            }
        }
    }
    public function destroy(int $id){
        $product = Product::find($id);
        if(!$product){
            return response()->json([
                'status' => 404,
                'message'=> 'Product Not Found'
            ],404);
        }else{
            $product->delete();

            return response()->json([
                'status'=>200,
                'message'=>'Product deleted Successfully'
            ],200);
        }
    }
}
