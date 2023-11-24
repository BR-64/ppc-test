<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\OrderitemRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\UserRelationManager;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
                    Select::make('pay_method'),
                    TextInput::make('total_price')->label('item price (incl Vat)'),
                    TextInput::make('discount_base')->label('discount'),
                    // Select::make('shipping')
                    // ->options([
                    //     'preparing' => 'preparing',
                    //     'shippped' => 'shippped',
                    // ])
                ])->columns(3),
               

                    Forms\Components\Fieldset::make('Shipping Info')->schema([
                        TextInput::make('ship_method'),
                        TextInput::make('shipping')->label('shipping cost'),
                        TextInput::make('insurance'),
                        TextInput::make('tracking'),
                    ]),
                    Fieldset::make('Customer Info')
                    ->relationship('customer')
                    ->schema([
                        TextInput::make('first_name'),
                        TextInput::make('last_name'),
                        TextInput::make('customer_name'),
                        TextInput::make('customer_taxid'),
                        TextInput::make('phone'),
                        Textarea::make('comment'),
                    ]),
                    Fieldset::make('Shipping Address')
                    ->relationship('bill')
                    ->schema([
                        TextInput::make('address1'),
                        TextInput::make('address2'),
                        TextInput::make('city'),
                        TextInput::make('state'),
                        TextInput::make('zipcode'),
                        TextInput::make('country_code'),
                    ]),

                

                
                Fieldset::make('Billing')
                    ->relationship('bill')
                    ->schema([
                        TextInput::make('address1'),
                        TextInput::make('address2'),
                        TextInput::make('city'),
                        TextInput::make('state'),
                        TextInput::make('zipcode'),
                        TextInput::make('country_code'),
                        
                    ]),

                    TextInput::make('fullprice')->label('net'),


        Select::make('status')
            ->options([
                'unpaid' => 'unpaid',
                'paid' => 'paid',
                'quotation' => 'quotation',
            ])
            ->required(),
        //         //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Order ID')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at'),
                // Tables\Columns\TextColumn::make('id')->label('Doc Code'),
               TextColumn::make('customer.first_name')->searchable(),
               TextColumn::make('customer.phone')->searchable()->label('phone'),

            //    TextColumn::make('bill.address1')->label('billing'),
               TextColumn::make('items_sum_quantity')
                ->label('Quantity')
                ->sum('items','quantity'),
                Tables\Columns\TextColumn::make('total_price')->label('item price'),
                Tables\Columns\TextColumn::make('discount_base')->label('discount'),
                Tables\Columns\TextColumn::make('fullprice')->label('net'),
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
