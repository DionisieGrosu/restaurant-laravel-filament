<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use Illuminate\Support\Str;
use App\Filament\Resources\ServiceResource;
use App\Models\Service;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = Str::slug($data['title']);

        return $data;
    }

    protected function afterSave(): void
    {
        // DeleteUnusedImages::dispatch(Food::all())->afterCommit();
        $files = Storage::disk('public')->allFiles('services');
        foreach ($files as $file) {
            $check_if_exists = Service::where('img', $file)->first();
            if (!$check_if_exists) {
                Storage::disk('public')->delete($file);
            }
        }
    }
}
