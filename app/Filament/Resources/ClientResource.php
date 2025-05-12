<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Shop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')->required()->maxLength(128),
                Forms\Components\TextInput::make('access_code')->required()->maxLength(255),
                Forms\Components\TextInput::make('code')->numeric(),
                Forms\Components\Select::make('status')
                    ->options([
                        'DISTRIBUTIE' => 'DISTRIBUTIE',
                        'B2B' => 'B2B',
                        'CORPORATE' => 'CORPORATE',
                        'ONLINE' => 'ONLINE',
                        'FURNIZORI EXTERNI' => 'FURNIZORI EXTERNI',
                        'ANGAJATI' => 'ANGAJATI',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('name')->maxLength(128),
                Forms\Components\TextInput::make('registration_number')->maxLength(128),
                Forms\Components\TextInput::make('vat')->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('vat'),
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }    
}
