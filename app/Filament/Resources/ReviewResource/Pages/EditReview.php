<?php

namespace App\Filament\Resources\ReviewResource\Pages;

use Illuminate\Support\Str;
use App\Filament\Resources\ReviewResource;
use App\Models\Review;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditReview extends EditRecord
{
    protected static string $resource = ReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // DeleteUnusedImages::dispatch(Food::all())->afterCommit();
        $files = Storage::disk('public')->allFiles('reviews');
        foreach ($files as $file) {
            $check_if_exists = Review::where('img', $file)->first();
            if (!$check_if_exists) {
                Storage::disk('public')->delete($file);
            }
        }
    }
}
