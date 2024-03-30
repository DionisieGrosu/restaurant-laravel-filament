<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return response()->json(['success' => true, 'data' => CategoryResource::collection(Category::active()->get())]);
    }

    public function item(Category $category)
    {
        return response()->json(['success' => true, 'data' => new CategoryResource($category)]);
    }
}
