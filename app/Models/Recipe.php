<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Recipe extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['html', 'is_my_favourite'];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // modify image path
    public function getImageAttribute($value): string
    {
        return asset('images/' . $value);
    }

    public function getStepsAttribute($value): array
    {
        return explode("|", $value);
    }

    public function getIngredientsAttribute($value): array
    {
        return explode("|", $value);
    }

    public function getHtmlAttribute(): string
    {
        // return HTML of the recipe: ingredients and instructions
        $html = '<h3>Ingredients</h3>';
        $html .= '<ul>';
        foreach($this->ingredients as $ingredient) {
            $html .= '<li>' . $ingredient . '</li>';
        }
        $html .= '</ul>';
        $html .= '<h3>Instructions</h3>';
        $html .= '<ol>';
        foreach($this->steps as $instruction) {
            $html .= '<li>' . $instruction . '</li>';
        }
        $html .= '</ol>';
        return $html;
    }

    public function getIsMyFavouriteAttribute(): bool
    {
        if(!Auth::guard('api')->check()) return false;
        $user_id = Auth::guard('api')->id();
        $fav = UserFavouriteRecipe::where('user_id', $user_id)->where('recipe_id', $this->id)->first();
        return (bool)$fav;
    }
}
