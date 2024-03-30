<?php

namespace App\Filament\Resources;

use App\Enums\RolesEnum;
use App\Filament\Resources\FoodsResource\Pages;
use App\Filament\Resources\FoodsResource\RelationManagers;
use App\Models\Category;
use App\Models\Food;
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

class FoodsResource extends Resource
{
    protected static ?string $model = Food::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Foods';

    protected static ?string $title = 'Foods';

    protected static ?string $navigationLabel = 'Foods';

    protected ?string $heading = 'Foods';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->required()
                    ->options(Category::all()->pluck('title', 'id')),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('seo_title')
                    ->maxLength(255),
                Forms\Components\TextInput::make('seo_keywords')
                    ->maxLength(255),
                Forms\Components\RichEditor::make('seo_desc')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('img')
                    ->image()
                    ->maxSize(1024)
                    ->imageEditor()
                    ->disk('public')
                    ->directory('foods')
                    ->visibility('public'),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->default(0.000)
                    ->prefix('$'),
                Forms\Components\Toggle::make('is_active')->default('active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('category_id')
                    ->label('Category')
                    ->options(Category::all()->pluck('title', 'id'))
                    ->searchable(),
                Tables\Columns\ImageColumn::make('img'),
                Tables\Columns\TextColumn::make('price')
                    ->money(),
                Tables\Columns\ToggleColumn::make('is_active')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->after(function (Food $record) {
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
                            $not_authorized = !in_array(RolesEnum::from(Auth::user()->role), [RolesEnum::ADMIN]);
                            if ($not_authorized) {
                                Notification::make()
                                    ->title('Errors!')
                                    ->body("Can not delete this foods!")
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
            'index' => Pages\ListFoods::route('/'),
            'create' => Pages\CreateFoods::route('/create'),
            'view' => Pages\ViewFoods::route('/{record}'),
            'edit' => Pages\EditFoods::route('/{record}/edit'),
        ];
    }
}
