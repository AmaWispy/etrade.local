<?php

namespace App\Filament\Resources\Shop;

use Filament\Forms;
use Filament\Tables;
use App\Models\Address;
use Filament\Forms\Form;
use App\Models\Shop\Order;
use Filament\Tables\Table;
use Squire\Models\Currency;
use App\Models\Shop\Product;
use Illuminate\Support\Carbon;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Forms\Components\AddressForm;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Shop\OrderResource\Pages;
use App\Filament\Resources\Shop\OrderResource\RelationManagers;
use App\Filament\Resources\Shop\OrderResource\Widgets\OrderStats;
use Filament\Infolists\Components\TextEntry;

class OrderResource extends Resource
{
    // Temporary disabled, remove or comment line below to enable
    protected static bool $shouldRegisterNavigation = false;
    
    protected static ?string $model = Order::class;

    protected static ?string $slug = 'shop/orders';

    protected static ?string $recordTitleAttribute = 'number';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema(static::getFormSchema())
                            ->columns(2),

                        /*Forms\Components\Section::make('Order items')
                            ->schema(static::getFormSchema('items')),*/
                    ])
                    ->columnSpan(['lg' => fn (?Order $record) => $record === null ? 3 : 2]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Fieldset::make('Contacts')
                            ->schema([
                                Forms\Components\Placeholder::make('name')
                                    ->label('Name')
                                    ->content(fn (Order $record): ?string => $record->customer->name),
                                Forms\Components\Placeholder::make('phone')
                                    ->label('Phone')
                                    ->content(fn (Order $record): ?string => $record->customer->phone),
                                Forms\Components\Placeholder::make('email')
                                    ->label('Email')
                                    ->content(fn (Order $record): ?string => $record->customer->email),
                            ])
                            ->columns(2),
                        Forms\Components\Fieldset::make('Delivery')
                            ->schema([
                                Forms\Components\Placeholder::make('street')
                                    ->label('Street')
                                    ->content(fn (Order $record): ?string => $record->address->street),
                                Forms\Components\Placeholder::make('house_number')
                                    ->label('House Nr')
                                    ->content(fn (Order $record): ?string => $record->address->house_number),
                                Forms\Components\Placeholder::make('appartament_number')
                                    ->label('Appartament')
                                    ->content(fn (Order $record): ?string => $record->address->appartament_number),
                                Forms\Components\Placeholder::make('entrance')
                                    ->label('Entrance')
                                    ->content(fn (Order $record): ?string => $record->address->entrance),
                                Forms\Components\Placeholder::make('floor')
                                    ->label('Floor')
                                    ->content(fn (Order $record): ?string => $record->address->floor),
                                Forms\Components\Placeholder::make('intercom')
                                    ->label('Intercom')
                                    ->content(fn (Order $record): ?string => $record->address->intercom),
                            ])
                            ->columns(2),
                        Forms\Components\Fieldset::make('Totals')
                            ->schema([
                                Forms\Components\Placeholder::make('subtotal')
                                    ->label('Subtotal')
                                    ->content(fn (Order $record): ?string => $record->subtotal),
                                Forms\Components\Placeholder::make('shipping')
                                    ->label('Shipping')
                                    ->content(fn (Order $record): ?string => $record->shipping),
                                Forms\Components\Placeholder::make('fixed_time')
                                    ->label('Fixed Time ')
                                    ->content(fn (Order $record): ?string => $record->fixed_time),
                                Forms\Components\Placeholder::make('total')
                                    ->label('Total')
                                    ->content(fn (Order $record): ?string => $record->total),
                            ])
                            ->columns(2),
                        Forms\Components\Fieldset::make('Date')
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Created at')
                                    ->content(fn (Order $record): ?string => $record->created_at?->diffForHumans()),
                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Last modified at')
                                    ->content(fn (Order $record): ?string => $record->updated_at?->diffForHumans()),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Order $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->date()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'info' => 'new',
                        'warning' => 'pending',
                        'success' => 'processing',
                        //'success' => fn ($state) => in_array($state, ['delivered', 'shipped']),
                    ]),
                Tables\Columns\TextColumn::make('subtotal')
                    ->searchable()
                    ->sortable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make(),
                    ]),
                Tables\Columns\TextColumn::make('shipping')
                    ->label('Shipping cost')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make(),
                    ]),
                Tables\Columns\TextColumn::make('fixed_time')
                    ->label('Fixed time cost')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make(),
                    ]),
                Tables\Columns\TextColumn::make('total')
                    ->searchable()
                    ->sortable()
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make(),
                    ]),
                
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                        Forms\Components\DatePicker::make('created_until')
                            ->placeholder(fn ($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Order from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Order until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
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
            ])
            ->groups([
                Tables\Grouping\Group::make('created_at')
                    ->label('Order Date')
                    ->date()
                    ->collapsible(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
            //RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScope(SoftDeletingScope::class);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['number', 'customer.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Order $record */

        return [
            'Customer' => optional($record->customer)->name,
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['customer', 'items']);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::$model::where('status', 'new')->count();
    }

    public static function getFormSchema(string $section = null): array
    {
        if ($section === 'items') {
            return [
                Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('shop_product_id')
                            ->label('Product')
                            ->options(Product::query()->pluck('name', 'id'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('unit_price', Product::find($state)?->price ?? 0))
                            ->columnSpan([
                                'md' => 5,
                            ])
                            ->searchable(),

                        Forms\Components\TextInput::make('qty')
                            ->label('Quantity')
                            ->numeric()
                            ->default(1)
                            ->columnSpan([
                                'md' => 2,
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('unit_price')
                            ->label('Unit Price')
                            ->disabled()
                            ->dehydrated()
                            ->numeric()
                            ->required()
                            ->columnSpan([
                                'md' => 3,
                            ]),
                    ])
                    ->orderable()
                    ->defaultItems(1)
                    ->disableLabel()
                    ->columns([
                        'md' => 10,
                    ])
                    ->required(),
            ];
        }

        return [
            Forms\Components\Select::make('status')
                ->options([
                    Order::NEW => 'New',
                    Order::PENDING => 'Pending',
                    Order::VERIFICATION => 'Verification',
                    Order::PROCESSING => 'Processing',
                ])
                ->required()
                ->native(false),

            Forms\Components\Select::make('shop_customer_id')
                ->relationship('customer', 'name')
                ->live()
                ->searchable()
                ->required()
                ->createOptionForm([
                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->required()
                        ->email()
                        ->unique(),

                    Forms\Components\TextInput::make('phone'),
                ])
                ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                    return $action
                        ->modalHeading('Create customer')
                        ->modalButton('Create customer')
                        ->modalWidth('lg');
                }),
            
            Forms\Components\Select::make('shop_customer_address_id')
                ->relationship('address')
                ->options(function (Forms\Get $get): \Illuminate\Support\Collection {
                    $options = [];
                    $customer = \App\Models\Shop\Customer::find($get('shop_customer_id'));
                    if($customer){
                        foreach($customer->addresses()->get() as $address){
                            $options[$address->id] = $address->full_address;
                            // dd($options);
                        }
                    }
                    return collect($options);
                })
                ->searchable()
                ->native(false)
                ->required()
                ->createOptionForm([
                    Forms\Components\Hidden::make('hash')->default(null),
                    Forms\Components\Select::make('country')
                        ->label('Country')
                        ->options(\App\Models\Country::all()->pluck('name', 'iso3'))
                        ->default('MDA')
                        ->searchable()
                        ->live(),
                    Forms\Components\TextInput::make('other_country')
                        ->hidden(fn (Forms\Get $get): bool => $get('country') !== 'OTH'),
                    Forms\Components\Select::make('locality')
                        ->label('Locality')
                        ->options(function (Forms\Get $get): \Illuminate\Support\Collection {
                            $options = [];
                            $country = \App\Models\Country::where('iso3', $get('country'))->first();
                            if($country){
                                $options = \App\Models\City::where('country_id', $country->id)->pluck('name', 'code');
                            }
                            return collect($options);
                        })
                        ->searchable()
                        ->hidden(fn (Forms\Get $get): bool => $get('country') === 'OTH'),
                    Forms\Components\TextInput::make('other_locality')
                        ->hidden(fn (Forms\Get $get): bool => $get('country') !== 'OTH'),
                ])
                ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                    return $action
                        ->modalHeading('Create address')
                        ->modalButton('Create address')
                        ->modalWidth('lg');
                })
                ->columnSpan('full'),

            Forms\Components\Select::make('shop_shipping_method_id')
                ->relationship('shippingMethod')
                ->getOptionLabelFromRecordUsing(fn (\App\Models\Shop\ShippingMethod $record) => $record->name)
                ->preload()
                ->searchable()
                ->required(),

            Forms\Components\Select::make('shop_payment_method_id')
                ->relationship('paymentMethod')
                ->getOptionLabelFromRecordUsing(fn (\App\Models\Shop\PaymentMethod $record) => $record->name)
                ->preload()
                ->searchable()
                ->required(),

            AddressForm::make('address')
                ->columnSpan('full'),

            Forms\Components\MarkdownEditor::make('notes')
                ->label('Card')
                ->columnSpan('full'),
        ];
    }
}
