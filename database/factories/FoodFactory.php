<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Food>
 */
class FoodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(6, false);
        $slug = Str::slug($title);
        $dirPath = Storage::disk('public')->path('') . 'foods';
        if (!File::isDirectory($dirPath)) {
            File::makeDirectory($dirPath, 0777, true, true);
        }
        return [
            'title' => $title,
            'slug' => $slug,
            'description' => fake()->text(512),
            'category_id' => Category::all()->random(1)->first()->id,
            'seo_title' => $title,
            'seo_desc' => fake()->text(512),
            'seo_keywords' => fake()->text(256),
            'img' => 'foods/' . fake()->image(Storage::disk('public')->path('') . '/foods/', 640, 480, null, false),
            'price' => fake()->randomFloat(3, 0, 10000),
            'is_active' => fake()->randomElement([1, 0])

        ];
    }
}
