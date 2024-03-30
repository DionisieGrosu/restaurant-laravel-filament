<?php

namespace App\Filament\Resources\CategoriesResource\Pages;

use \Illuminate\Support\Str;
use App\Filament\Resources\CategoriesResource;
use App\Models\Category;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateCategories extends CreateRecord
{
    protected static string $resource = CategoriesResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = Str::slug($data['title']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        // DeleteUnusedImages::dispatch(Food::all())->afterCommit();
        $files = Storage::disk('public')->allFiles('categories');
        foreach ($files as $file) {
            $check_if_exists = Category::where('icon', $file)->first();
            if (!$check_if_exists) {
                Storage::disk('public')->delete($file);
            }
        }
    }
}
