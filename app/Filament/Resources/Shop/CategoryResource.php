<?php

namespace App\Filament\Resources\Shop;

use App\Filament\Resources\Shop\CategoryResource\Pages;
use App\Filament\Resources\Shop\CategoryResource\RelationManagers;
use App\Models\Shop\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Resources\Concerns\Translatable;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str; 

class CategoryResource extends Resource
{
    use Translatable;
    
    // Temporary disabled, remove or comment line below to enable
    // protected static bool $shouldRegisterNavigation = false;
    
    protected static ?string $model = Category::class;

    protected static ?string $slug = 'shop/categories';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxValue(50)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                        Forms\Components\TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(Category::class, 'slug', ignoreRecord: true),

                        Forms\Components\MarkdownEditor::make('description')
                            ->label('Description'),
                        Forms\Components\Select::make('attribute')
                        ->multiple()
                        ->relationship('managedAttrtibute')
                        ->options(\App\Models\Shop\Attribute::query()->pluck('name', 'id')) // TODO: СДЕЛАТЬ ТАБЛИЦУ ДЛЯ СВЯЗЕЙ МЕЖДУ КАТЕГОРИЕМ И ФИЛЬТРОМ
                        ->label('Filters')
                        ->searchable()
                        ->preload()
                        ->live(),
                            /*->createOptionForm($attForm)
                            ->createOptionUsing(function (array $data) {
                                $model = \App\Models\Shop\Attribute::create($data);
                                return $model->id;
                            }),*/
                        ]) 
                    ->columnSpan(['lg' => 2]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Toggle::make('is_visible')
                            ->label('Visible to customers.')
                            ->default(true),

                        Forms\Components\Select::make('parent_id')
                            ->label('Parent')
                            //->relationship('parent', 'name', fn (Builder $query) => $query->where('parent_id', null)) // Take just top lvl categories
                            ->options(fn () => collect(Category::buildOptions()))
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn (Category $record) => $record->name)
                            ->searchable()
                            ->placeholder('Select parent category')
                            ->allowHtml(),

                        Forms\Components\FileUpload::make('image')->image()->disk('public'),

                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (Category $record): ?string => $record->created_at?->diffForHumans())
                            ->hidden(fn (?Category $record) => $record === null),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (Category $record): ?string => $record->updated_at?->diffForHumans())
                            ->hidden(fn (?Category $record) => $record === null),
                    ])
                    ->columnSpan(['lg' => 1]),
                    //->hidden(fn (?Category $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Parent')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visibility')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated Date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->groupedBulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(function () {
                        Notification::make()
                            ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                            ->warning()
                            ->send();
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
