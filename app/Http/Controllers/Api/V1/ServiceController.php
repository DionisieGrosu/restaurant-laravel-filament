<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return response()->json(['success' => true, 'data' => ServiceResource::collection(Service::active()->get())]);
    }

    public function item(Service $category)
    {
        return response()->json(['success' => true, 'data' => new ServiceResource($category)]);
    }
}
