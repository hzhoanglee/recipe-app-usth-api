<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\RecipeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function explore(Request $request): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Recipe::with('category')->paginate(10);
    }

    public function recipeData(Request $request): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|\Illuminate\Http\JsonResponse|array
    {
        $recipe = Recipe::with('category')->find($request->recipe);
        if($recipe) {
            $recipe->views++;
            $recipe->save();
            $recipe->html = str_replace("\n", "<br>", $recipe->html);
            return $recipe;
        }
        else
            return response()->json(['message' => 'Recipe not found'], 404);
    }

    public function searchRecipe(Request $request): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Recipe::with('category')->where('name', 'like', '%'.$request->query_string.'%')
            ->orWhere('description', 'like', '%'.$request->query_string.'%')
            ->orWhere('ingredients', 'like', '%'.$request->query_string.'%')
            ->orWhere('level', 'like', '%'.$request->query_string.'%')
            ->orWhere('serving', 'like', '%'.$request->query_string.'%')
            ->orWhereHas('category', function($query) use ($request) {
                $query->where('name', 'like', '%'.$request->query_string.'%');
            })
            ->paginate(100);
    }

    public function featuredRecipe(Request $request): \Illuminate\Contracts\Pagination\Paginator
    {
        return Recipe::with('category')->inRandomOrder()->limit(4)->simplePaginate(4);
    }

    public function recipeRequest(Request $request) {
        $user_id = 0;
        $name = $request->name;
        if(Auth::guard('api')->check()) {
            $user_id = Auth::guard('api')->user()->id;
        }
        RecipeRequest::create([
            'request_by' => $user_id,
            'name' => $name
        ]);
        return response()->json(['message' => 'Request submitted successfully'], 200);
    }
}
