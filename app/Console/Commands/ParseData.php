<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Recipe;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ParseData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parse-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Truncate the tables
        Recipe::truncate();
        Category::truncate();
        $categories = Storage::disk('local')->directories('raw_recipes');
        foreach ($categories as $category) {
            dump("Calling $category");
            $c = Category::create([
                'name' => basename($category),
            ]);

            $recipes = Storage::disk('local')->files($category);
            foreach ($recipes as $recipe) {
                if (str_contains($recipe, '.DS_Store')) {
                    continue;
                }
                dump("Calling $recipe");
                $recipe_json = Storage::disk('local')->get($recipe);
                $recipe_data = json_decode($recipe_json, true);
                if($recipe_data === null) {
                    dump("Error parsing $recipe");
                    continue;
                }
                $ingredient_str = '';
                $steps_str = '';


                foreach ($recipe_data['ingredients'] as $ingredient) {
                    $ingredient_str .= $ingredient . "|";
                }
                $ingredient_str = substr($ingredient_str, 0, -1);

                foreach ($recipe_data['method'] as $step) {
                    $steps_str .= $step['stepDescription'] . "|";
                }
                $steps_str = substr($steps_str, 0, -1);

                $r = Recipe::create([
                    'name' => $recipe_data['title'],
                    'description' => $recipe_data['description'],
                    'image' => null,
                    'ingredients' => $ingredient_str,
                    'steps' => $steps_str,
                    'category' => $c->id,
                    'order' => 9999,
                    'original_url' => $recipe_data['url'],
                    'serving' => $recipe_data['servings'],
                    'level' => $recipe_data['skillLevel'],
                    'prep_time' => $recipe_data['prepTime'],
                    'cook_time' => rand(20, 60) . ' mins',
                    'review' => rand(3, 5)
                ]);
                $url = $recipe_data['url'];
                dump("Getting meta for {$r->name}");
                $meta = "";
                ini_set('default_socket_timeout', 15);
                $maxRetries = 3;
                $retryCount = 0;
                while ($meta == "" && $retryCount < $maxRetries) {
                    try {
                        dump("Getting meta for {$r->name}");
                        $meta = get_meta_tags($url);
                    } catch (Exception $e) {
                        dump("Timeout occurred: " . $e->getMessage());
                        $retryCount++;
                    }
                }
                if ($meta == "") {
                    dump("Failed to retrieve meta data after $maxRetries retries");
                } else {
                    dump("Meta data retrieved: ", $meta);
                }
                $image = $meta['og:image'];
                $image = preg_replace('/\?.*/', '', $image);
                $image_data = file_get_contents($image);
                $image_name = basename($image);
                Storage::disk('public_dir')->put('images/' . $image_name, $image_data);
                dump("Saved image to {$image_name}");
                $r->image = $image_name;
                $r->save();
                dump("Saved {$r->name}");
                sleep(1);
            }
        }
    }
}
