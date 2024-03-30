<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        return response()->json(['success' => true, 'data' => MenuResource::collection(Menu::active()->get())]);
    }

    public function item(Menu $menu)
    {
        return response()->json(['success' => true, 'data' => new MenuResource($menu)]);
    }
}
