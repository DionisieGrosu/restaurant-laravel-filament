<?php

namespace App\Filament\Resources;

use App\Enums\RolesEnum;
use App\Filament\Resources\CategoriesResource\Pages;
use App\Filament\Resources\CategoriesResource\RelationManagers;
use App\Models\Category;
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

class CategoriesResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Foods';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('seo_title')
                    ->maxLength(255),
                Forms\Components\TextInput::make('seo_keywords')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('icon')
                    ->image()
                    ->maxSize(1024)
                    ->imageEditor()
                    ->disk('public')
                    ->directory('categories')
                    ->visibility('public'),
                Forms\Components\RichEditor::make('description')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('seo_desc')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')->default('active'),
                Forms\Components\Toggle::make('on_main'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('icon'),
                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->after(function (Category $record) {
                        if ($record->icon) {
                            Storage::disk('public')->delete($record->icon);
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
                                if ($record->icon) {
                                    Storage::disk('public')->delete($record->icon);
                                }
                            }
                        })
                        ->before(function ($records, Tables\Actions\DeleteBulkAction $action) {
                            $not_authorized = !in_array(RolesEnum::from(Auth::user()->role), [RolesEnum::ADMIN]);
                            if ($not_authorized) {
                                Notification::make()
                                    ->title('Errors!')
                                    ->body("Can not delete this categories!")
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategories::route('/create'),
            'view' => Pages\ViewCategories::route('/{record}'),
            'edit' => Pages\EditCategories::route('/{record}/edit'),
        ];
    }
}
