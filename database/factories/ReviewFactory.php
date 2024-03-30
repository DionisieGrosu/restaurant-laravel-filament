<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $dirPath = Storage::disk('public')->path('') . 'reviews';
        if (!File::isDirectory($dirPath)) {
            File::makeDirectory($dirPath, 0777, true, true);
        }
        return [
            'title' => fake()->sentence(6, false),
            'name' => fake()->name(),
            'address' => fake()->address(),
            'text' => fake()->text(512),
            'img' => 'reviews/' . fake()->image(Storage::disk('public')->path('') . '/reviews/', 640, 480, null, false),
            'is_active' => fake()->randomElement([1, 0])

        ];
    }
}
