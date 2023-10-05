<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Category::orderBy('order')->get();
    }

    public function categoryData(Request $request) {
        $category = Category::find($request->category);
        return $category->recipes()->with('category')->paginate(10);
    }

}
