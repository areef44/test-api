<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
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
         $categories = Category::query()->get();
         return response()->json([
             "status" => true,
             "message" => "",
             "data" => $categories
         ]);
     }
 
 
     function show($id)
     {
         $categories = Category::query()
             ->where("id", $id)
             ->first();
         if ($categories == null) {
             return response()->json([
                 "status" => false,
                 "message" => "Category not found",
                 "data" => null
             ]);
         }
         return response()->json([
             "status" => true,
             "message" => "",
             "data" => $categories
 
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
 
 
         if (!isset($payload["description"])) {
             return response()->json([
                 "status" => false,
                 "message" => "description must exist",
                 "data" => null
             ]);
         }
 
         $categories = Category::query()->create($payload);
         return response()->json([
             "status" => true,
             "message" => "",
             "data" => $categories
         ]);
     }
 
     function update(Request $request, $id)
     {
         $categories = Category::query()->where("id", $id)->first();
 
         if ($categories == null) {
             return response()->json([
                 "status" => false,
                 "message" => "Category not found",
                 "data" => null
             ]);
         }
 
 
         $categories->fill($request->all());
 
         $categories->save();
 
 
         return response()->json([
             "status" => true,
             "message" => "Category has been successfully modified",
             "data" => $categories->makeHidden([
                 "id",
                 "created_at",
                 "updated_at"
             ])
         ]);
     }
 
 
     function destroy($id)
     {
         $categories = Category::query()
             ->where("id", $id)
             ->first()
             ->delete();

        $products = Product::query()
             ->where("categories_id", $id)
             ->delete();
 
         if ($categories == null) {
             return response()->json([
                 "status" => false,
                 "message" => "Category not found",
                 "data" => null
             ]);
         }
         return response()->json([
             "status" => true,
             "message" => "Category has been deleted successfully",
             "data" => $categories
 
         ]);
     }
}
