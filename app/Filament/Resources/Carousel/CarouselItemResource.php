<?php

namespace App\Filament\Resources\Carousel;

use App\Filament\Resources\Carousel\CarouselItemResource\Pages;
use App\Filament\Resources\Carousel\CarouselItemResource\RelationManagers;
use App\Models\Carousel\CarouselItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Resources\Concerns\Translatable;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CarouselItemResource extends Resource
{
    use Translatable;

    protected static ?string $model = CarouselItem::class;

    protected static ?string $navigationGroup = 'Carousels';

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?int $navigationSort = 1;

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
                        Forms\Components\Select::make('carousel_id')
                            ->relationship('carousel', 'title')
                            ->getOptionLabelFromRecordUsing(fn (\App\Models\Carousel\Carousel $record) => $record->title)
                            ->preload()
                            ->searchable(),
                        Forms\Components\Toggle::make('is_active')
                            ->required(),
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->label('Image')
                            ->disableLabel(),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image'),
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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListCarouselItems::route('/'),
            'create' => Pages\CreateCarouselItem::route('/create'),
            'edit' => Pages\EditCarouselItem::route('/{record}/edit'),
        ];
    }    
}
