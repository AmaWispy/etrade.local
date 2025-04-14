<?php

namespace App\Filament\Resources\Shop;

use App\Filament\Resources\Shop\DiscountResource\Pages;
use App\Filament\Resources\Shop\DiscountResource\RelationManagers;
use App\Models\Shop\Discount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\RichEditor::make('description'),
                        Forms\Components\Repeater::make('apply_to')
                            ->schema([
                                Forms\Components\Select::make('entity')
                                    ->options([
                                        'App\Models\Shop\Category' => 'Category',
                                        'App\Models\Shop\Product' => 'Product'
                                    ])
                                    ->native(false)
                                    ->reactive(),
                                Forms\Components\Select::make('items')
                                    ->label('List')
                                    ->multiple()
                                    ->options(function (callable $get, callable $set) {
                                        $options = [];
                                        /**
                                         * Get options according to selected type
                                         */
                                        if(null !== $get('entity')){
                                            $class = $get('entity');
                                            $model = new $class();
                                            $options = $model->all()->pluck('name', 'id');
                                        }
                                        return $options;
                                    })
                                    ->native(false)
                                    ->searchable(),
                            ]),
                        Forms\Components\Fieldset::make('availability')
                            ->schema([ 
                                Forms\Components\DatePicker::make('start_date')
                                    ->default(\Illuminate\Support\Carbon::today()->format('Y-m-d'))
                                    ->label('Start Date')
                                    ->helperText('Run discounts starting with this date'),
                                Forms\Components\DatePicker::make('end_date')
                                    ->default(\Illuminate\Support\Carbon::today()->addDays(14)->format('Y-m-d'))
                                    ->label('End Date')
                                    ->helperText('Discount ends on this date'),
                                Forms\Components\TimePicker::make('start_time')
                                    ->seconds(false)
                                    ->default('00:00')
                                    ->label('From Time')
                                    ->helperText('Discount will be applied starting from'),
                                Forms\Components\TimePicker::make('end_time')
                                    ->seconds(false)
                                    ->default('23:59')
                                    ->label('To Time')
                                    ->helperText('Discount will be stopped on'),
                            ])
                            ->columns(2)
                    ])
                    ->columnSpan(['lg' => 2]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options([
                                'percent' => 'Subtract percentage',
                                'amount' => 'Subtract amount',
                                'price' => 'Set new price',
                            ])
                            ->native(false)
                            ->default('percent'),
                        Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                            ->default(5),
                        Forms\Components\Select::make('rounding')
                            ->options([
                                'no_rounding' => 'No rounding',
                                'nearest_five' => 'Nearest five',
                                'nearest_ten' => 'Nearest ten',
                            ])
                            ->native(false)
                            ->default('nearest_ten')
                            ->helperText('Discounted price rounding'),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(['lg' => 3]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscount::route('/create'),
            'edit' => Pages\EditDiscount::route('/{record}/edit'),
        ];
    }    
}
