<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\RelationManagers\OrderRelationManager;
use App\Filament\Resources\VoucherResource\Pages;
use App\Filament\Resources\VoucherResource\RelationManagers;
use App\Filament\Resources\VoucherResource\RelationManagers\OrdersRelationManager;
use App\Models\Voucher;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->unique(),
                Forms\Components\TextInput::make('discount_percent')
                    ->label('Discount (%)')
                    ->numeric()
                    ->default(10)
                    ->required()
                    ->extraInputAttributes(['min' => 1, 'max' => 100, 'step' => 1]),
                Forms\Components\TextInput::make('qty')
                    ->label('Quantity')
                    ->numeric()
                    ->default(10)
                    ->extraInputAttributes(['min' => 1, 'step' => 1])
                    ->required(),
                Forms\Components\DatePicker::make('valid_until')
                    ->required()

                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('discount_percent')->label('Discount (%)'),
                Tables\Columns\TextColumn::make('qty'),
                Tables\Columns\TextColumn::make('valid_until'),
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            OrdersRelationManager::class
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVouchers::route('/'),
            // 'create' => Pages\CreateVoucher::route('/create'),
            // 'edit' => Pages\EditVoucher::route('/{record}/edit'),
            // 'index' => Pages\ManageVouchers::route('/'),

        ];
    }    
}
