<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\OrderitemRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\UserRelationManager;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Layout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Order Info')->schema([
                    TextInput::make('id')->label('Order Number')->disabled(),
                    TextInput::make('created_at')->disabled(),
                    TextInput::make('total_price'),
                    Select::make('shipping')
                    ->options([

                    ]),
                ])->columns(3),
                Forms\Components\Fieldset::make('Customer Info')->schema([
                    Select::make('name')
                        ->relationship('customer','first_name')->disabled()
                        ->label('Name'),
                    Select::make('phone')
                        ->relationship('customer','phone')->disabled()
                        ->label('Phone'),
                    Select::make('tax_id')
                        ->relationship('customer','customer_taxid')
                        ->label('Tax ID'),
                ])->columns(3),
                Forms\Components\TextInput::make('status'),
                // TextInput::make('status')->required(),
        Select::make('status')
            ->options([
                'unpaid' => 'unpaid',
                'paid' => 'paid',
                'quotation' => 'quotation',
            ])
            ->required(),
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at'),
                Tables\Columns\TextColumn::make('id')->label('Doc Code'),
               TextColumn::make('customer.first_name'),
               TextColumn::make('items_sum_quantity')
                ->label('Quantity')
                ->sum('items','quantity'),
                Tables\Columns\TextColumn::make('total_price'),
                Tables\Columns\TextColumn::make('status')->sortable(),
                //
            ])
            ->defaultSort('created_at','desc')
            ->filters([
                Tables\Filters\Filter::make('created_at')
                ->form([
                    DatePicker::make('created_from'),
                    DatePicker::make('created_until'),
                    ])
            ->query(function ($query, array $data){
                return $query
                    ->when($data['created_from'],
                    fn($query)=>$query->whereDate('created_at','>=',$data['created_from']))
                    ->when($data['created_until'],
                    fn($query)=>$query->whereDate('created_at','<=',$data['created_until']));
            })
        ])
    
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
        OrderitemRelationManager::class

            //
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
}
