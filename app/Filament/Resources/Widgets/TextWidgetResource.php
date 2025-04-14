<?php

namespace App\Filament\Resources\Widgets;

use App\Filament\Resources\Widgets\TextWidgetResource\Pages;
use App\Filament\Resources\Widgets\TextWidgetResource\RelationManagers;
use App\Models\Widgets\WidgetGroup;
use App\Models\Widgets\TextWidget;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Resources\Concerns\Translatable;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class TextWidgetResource extends Resource
{
    use Translatable;
    
    protected static ?string $model = TextWidget::class;

    protected static ?string $navigationGroup = 'Widgets';

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('title_top')
                                    ->maxLength(2048),
                                Forms\Components\TextInput::make('title')
                                    ->maxLength(2048),
                                Forms\Components\TextInput::make('title_bottom')
                                    ->maxLength(2048),
                                Forms\Components\RichEditor::make('content'),
                            ])
                            ->columnSpan(['lg' => 2]),
                Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Toggle::make('is_active'),
                                Forms\Components\TextInput::make('key')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\FileUpload::make('image'),
                                Forms\Components\Select::make('widget_group_id')
                                    ->relationship('group', 'title')
                                    ->getOptionLabelFromRecordUsing(fn (WidgetGroup $record) => $record->title)
                                    ->preload()
                                    ->searchable(),
                            ])
                            ->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //Tables\Columns\TextColumn::make('widget_groups.title'),
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
            'index' => Pages\ListTextWidgets::route('/'),
            'create' => Pages\CreateTextWidget::route('/create'),
            'edit' => Pages\EditTextWidget::route('/{record}/edit'),
        ];
    }    
}
