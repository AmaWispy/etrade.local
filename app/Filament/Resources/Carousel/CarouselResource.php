<?php

namespace App\Filament\Resources\Carousel;

use App\Filament\Resources\Carousel\CarouselResource\Pages;
use App\Filament\Resources\Carousel\CarouselResource\RelationManagers;
use App\Models\Carousel\Carousel;
use Filament\Forms;
use Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Resources\Concerns\Translatable;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarouselResource extends Resource
{
    use Translatable;

    protected static ?string $model = Carousel::class;

    protected static ?string $navigationGroup = 'Carousels';

    protected static ?string $navigationIcon = 'heroicon-o-film';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->maxLength(2048),
                                Forms\Components\TextInput::make('subtitle')
                                    ->maxLength(2048),
                                Forms\Components\RichEditor::make('content'),
                            ])
                            ->columnSpan(['lg' => 2]),
                            Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->required(),
                                Forms\Components\TextInput::make('key')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key'),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListCarousels::route('/'),
            'create' => Pages\CreateCarousel::route('/create'),
            'edit' => Pages\EditCarousel::route('/{record}/edit'),
        ];
    }    
}
