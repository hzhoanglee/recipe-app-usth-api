<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\UserFavouriteRecipe;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    public function newFavourite(Request $request): \Illuminate\Http\JsonResponse
    {
        $recipe_id = $request->recipe_id;
        $user_id = auth()->user()->id;
        if(Recipe::find($recipe_id)) {
            $fav = UserFavouriteRecipe::where('user_id', $user_id)->where('recipe_id', $recipe_id)->first();
            if ($fav) {
                return response()->json(['message' => 'Already in favourites'], 409);
            }
            UserFavouriteRecipe::create([
                'user_id' => $user_id,
                'recipe_id' => $recipe_id
            ]);
            return response()->json(['message' => 'Successfully added to favourites']);
        }
        return response()->json(['message' => 'Recipe not found'], 404);
    }

    public function deleteFavourite(Request $request): \Illuminate\Http\JsonResponse
    {
        $recipe_id = $request->recipe_id;
        $user_id = auth()->user()->id;
        $fav = UserFavouriteRecipe::where('user_id', $user_id)->where('recipe_id', $recipe_id)->first();
        if ($fav) {
            $fav->delete();
            return response()->json(['message' => 'Successfully deleted from favourites']);
        }
        return response()->json(['message' => 'Recipe not found in favourites'], 404);
    }

    public function listFavourite(Request $request): \Illuminate\Http\JsonResponse
    {
        $user_id = auth()->user()->id;
        // favourite recipes with recipe and recipe with category
        $favourites = UserFavouriteRecipe::with('recipe.category')->where('user_id', $user_id)->paginate(10);
        return response()->json($favourites);
    }
}
