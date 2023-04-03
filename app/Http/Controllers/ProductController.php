<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
     /**
     * CRUD
     * -list => index
     * -detail => show
     * -edit => update
     * -create => store
     * -delete => destroy
     */

     function index()
     {
         $product = Product::with("category")->get();
         return response()->json([
             "status" => true,
             "message" => "",
             "data" => $product
         ]);
     }
 
 
     function show($id)
     {
         $product = Product::with("category")
             ->where("id", $id)
             ->first();
         if ($product == null) {
             return response()->json([
                 "status" => false,
                 "message" => "Product not found",
                 "data" => null
             ]);
         }
         return response()->json([
             "status" => true,
             "message" => "",
             "data" => $product
 
         ]);
     }
 
 
     function store(Request $request)
 
     {
         $payload = $request->all();
         if (!isset($payload["name"])) {
             return response()->json([
                 "status" => false,
                 "message" => "name must exist",
                 "data" => null
             ]);
         }
 
         if (!isset($payload["price"])) {
             return response()->json([
                 "status" => false,
                 "message" => "price must exist",
                 "data" => null
             ]);
         }
 
 
         if (!isset($payload["description"])) {
             return response()->json([
                 "status" => false,
                 "message" => "description must exist",
                 "data" => null
             ]);
         }
 
         if (!isset($payload["categories_id"])) {
             return response()->json([
                 "status" => false,
                 "message" => "categories must exist",
                 "data" => null
             ]);
         }
 
         $picture = $request->file("picture");
         $filename = $picture->hashName();
         $picture->move("picture", $filename);
         $payload['picture'] = $request->getSchemeAndHttpHost() . "/picture/" . $filename;
 
 
 
         $product = Product::query()->create($payload);
         return response()->json([
             "status" => true,
             "message" => "",
             "data" => $product
         ]);
     }
 
     function update(Request $request, $id)
     {
         $product = Product::query()->where("id", $id)->first();
 
         if ($product == null) {
             return response()->json([
                 "status" => false,
                 "message" => "Product not found",
                 "data" => null
             ]);
         }
 
 
         $product->fill($request->all());
 
         $picture = $request->file("picture");
         if ($picture != null) {
             $filename = $picture->hashName();
             $picture->move("picture", $filename);
             $product->picture = $request->getSchemeAndHttpHost() . "/picture/" . $filename;
         }
 
         $product->save();
 
 
         return response()->json([
             "status" => true,
             "message" => "The product has been successfully modified",
             "data" => $product->makeHidden([
                 "id",
                 "created_at",
                 "updated_at"
             ])
         ]);
     }
 
 
     function destroy($id)
     {
         $products = Product::query()
             ->where("id", $id)
             ->first()
             ->delete();
 
         if ($products == null) {
             return response()->json([
                 "status" => false,
                 "message" => "product not found",
                 "data" => null
             ]);
         }
         return response()->json([
             "status" => true,
             "message" => "product has been deleted successfully",
             "data" => $products
 
         ]);
     }
}
