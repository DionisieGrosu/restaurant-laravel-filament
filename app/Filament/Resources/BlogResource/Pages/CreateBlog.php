<?php

namespace App\Filament\Resources\BlogResource\Pages;

use Illuminate\Support\Str;
use App\Filament\Resources\BlogResource;
use App\Models\Blog;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateBlog extends CreateRecord
{
    protected static string $resource = BlogResource::class;

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
        $files = Storage::disk('public')->allFiles('blogs');
        foreach ($files as $file) {
            $check_if_exists = Blog::where('img', $file)->first();
            if (!$check_if_exists) {
                Storage::disk('public')->delete($file);
            }
        }
    }
}
