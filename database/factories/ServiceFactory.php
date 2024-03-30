<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
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
        $dirPath = Storage::disk('public')->path('') . 'services';
        if (!File::isDirectory($dirPath)) {
            File::makeDirectory($dirPath, 0777, true, true);
        }
        return [
            'title' => $title,
            'slug' => $slug,
            'description' => fake()->text(512),
            'short_description' => fake()->text(512),
            'seo_title' => $title,
            'seo_desc' => fake()->text(512),
            'seo_keywords' => fake()->text(256),
            'img' => 'services/' . fake()->image(Storage::disk('public')->path('') . '/services/', 640, 480, null, false),
            'is_active' => fake()->randomElement([1, 0])

        ];
    }
}
