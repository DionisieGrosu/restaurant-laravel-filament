<?php

namespace App\Filament\Resources\FoodsResource\Pages;

use Illuminate\Support\Str;
use App\Filament\Resources\FoodsResource;
use App\Models\Food;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateFoods extends CreateRecord
{
    protected static string $resource = FoodsResource::class;

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
        $files = Storage::disk('public')->allFiles('foods');
        foreach ($files as $file) {
            $check_if_exists = Food::where('img', $file)->first();
            if (!$check_if_exists) {
                Storage::disk('public')->delete($file);
            }
        }
    }
}
