<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['user_name'];

    // get the user who requested the recipe
    public function getUserNameAttribute(): string
    {
        if($this->requested_by == 0) {
            return "Anonymous";
        }
        if(User::find($this->requested_by)) {
            return User::find($this->requested_by)->name;
        }
        return "Deleted user";
    }

}
