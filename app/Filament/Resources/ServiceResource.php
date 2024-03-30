<?php

namespace App\Filament\Resources;

use App\Enums\RolesEnum;
use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('description')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('short_description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('seo_title')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('seo_desc')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('seo_keywords')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('img')
                    ->image()
                    ->maxSize(1024)
                    ->imageEditor()
                    ->disk('public')
                    ->directory('services')
                    ->visibility('public'),
                Forms\Components\Toggle::make('is_active')
                    ->default('active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('img'),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->after(function (Service $record) {
                        if ($record->img) {
                            Storage::disk('public')->delete($record->img);
                        }
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->hidden(
                            fn (): bool => Auth::user()->role != RolesEnum::ADMIN->value
                        )
                        ->after(function ($records) {
                            foreach ($records as $record) {
                                if ($record->img) {
                                    Storage::disk('public')->delete($record->img);
                                }
                            }
                        })
                        ->before(function ($records, Tables\Actions\DeleteBulkAction $action) {
                            $ids = $records->pluck('id')->toArray();
                            $not_authorized = !in_array(RolesEnum::from(Auth::user()->role), [RolesEnum::ADMIN]);
                            if ($not_authorized) {
                                Notification::make()
                                    ->title('Errors!')
                                    ->body("Can not delete this menu items!")
                                    ->status('danger')
                                    ->send();
                                $action->cancel();
                            }
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'view' => Pages\ViewService::route('/{record}'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
