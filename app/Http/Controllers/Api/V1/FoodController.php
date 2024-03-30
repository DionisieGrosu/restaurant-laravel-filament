<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FoodResource;
use App\Models\Category;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index(Request $request)
    {

        $category = $request->query('category');
        if ($category) {
            $category = Category::select('id')->where('slug', $category)->firstOrFail();
            if ($category) {
                return response()->json(['success' => true, 'data' => FoodResource::collection(Food::active()->where('category_id', $category->id)->get())]);
            }
        } else {
            return response()->json(['success' => true, 'data' => FoodResource::collection(Food::active()->get())]);
        }
    }

    public function item(Food $category)
    {
        return response()->json(['success' => true, 'data' => new FoodResource($category)]);
    }
}
