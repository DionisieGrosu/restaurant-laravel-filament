<?php

namespace App\Filament\Resources;

use App\Enums\RolesEnum;
use App\Filament\Resources\MenuResource\Pages;
use App\Filament\Resources\MenuResource\RelationManagers;
use App\Models\Menu;
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

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                Forms\Components\FileUpload::make('img')
                    ->image()
                    ->maxSize(1024)
                    ->imageEditor()
                    ->disk('public')
                    ->directory('menus')
                    ->visibility('public'),
                Forms\Components\RichEditor::make('description')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('seo_desc')
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')->default('active'),
                Forms\Components\Toggle::make('on_top'),
                Forms\Components\Toggle::make('on_bottom'),
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
                Tables\Columns\ToggleColumn::make('on_top'),
                Tables\Columns\ToggleColumn::make('on_bottom'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->after(function (Menu $record) {
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
                            $exists = Menu::whereIn('id', $ids)->where('system', 1)->exists();
                            $not_authorized = !in_array(RolesEnum::from(Auth::user()->role), [RolesEnum::ADMIN]);
                            if ($exists || $not_authorized) {
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
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'view' => Pages\ViewMenu::route('/{record}'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
