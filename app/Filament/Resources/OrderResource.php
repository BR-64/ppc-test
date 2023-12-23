<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\OrderitemRelationManager;
use App\Filament\Resources\OrderResource\RelationManagers\UserRelationManager;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Layout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Actions as act;
use Filament\Forms\Components\Actions\Action;

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
                    TextInput::make('total_price')->label('item price (incl Vat)')->mask(fn (TextInput\Mask $mask) => $mask->money(prefix: '', thousandsSeparator: ',', decimalPlaces: 2)),
                    TextInput::make('discount_amount')->label('discount')
                    ->mask(fn (TextInput\Mask $mask) => $mask->money(prefix: '', thousandsSeparator: ',', decimalPlaces: 2)),
                    TextInput::make('fullprice')->label('net')->mask(fn (TextInput\Mask $mask) => $mask->money(prefix: '', thousandsSeparator: ',', decimalPlaces: 2))
                    // ->afterStateHydrated(function (TextInput $component, $state) {
                    //     $component->state(str_replace(".",",",$state));
                    // })
                    ,
                    TextInput::make('vc')->disabled(),
                    TextInput::make('enpro_doc')])->columns(3),
               

                    Forms\Components\Fieldset::make('Shipping Info')->schema([
                        TextInput::make('ship_method'),
                        TextInput::make('shipping')->label('shipping cost')
                        ->Mask(
                            fn (TextInput\Mask $mask) => $mask
                                ->patternBlocks([
                                    'money' => fn (Mask $mask) => $mask
                                    ->numeric()
                                    ->decimalPlaces(2)
                                    ->decimalSeparator('.')
                                    ->thousandsSeparator(',')
                                    ->mapToDecimalSeparator(['.'])
                                    ->padFractionalZeros()
                                    ->normalizeZeros(true)
                            ])
                            ->pattern('money')
                        ),
                        TextInput::make('insurance'),
                        TextInput::make('boxcount'),
                        TextInput::make('tracking'),
                        KeyValue::make('boxes')
                            // ->disabled(),
                        
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
                    ->relationship('ship')
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

                
                // act::make([
                //     Action::make('button name')
                //       ->action(fn()=>redirect()-> route('product.getalldata'))
                // ]),

                // Action::make('test'),

                // act::make([
                //     Action::make('custom')
                //         ->action(function () {
                //             //...
                //         }),
                //     ]),

                    TextInput::make('Paymentconfirm')
                        ->suffixAction(fn (?string $state): Action =>
                            Action::make('visit')
                                ->icon('heroicon-s-external-link')
                                ->url(
                                    filled($state) ? "https://{$state}" : null,
                                    shouldOpenInNewTab: true,
                                ),
                            ),
                    TextInput::make('Paymentconfirm')
                        ->suffixAction(fn (): Action =>
                            Action::make('visit')
                                ->icon('heroicon-s-external-link')
                                ->action(fn()=>redirect()-> route('admail_control'))

                            ),


                    // Action::make('visit')
                    //     ->icon('heroicon-s-external-link')
                    //     ->url(route('product.getalldata')),

                    TextInput::make('fullprice')->label('net')
                    ->Mask(
                        fn (TextInput\Mask $mask) => $mask
                            ->patternBlocks([
                                'money' => fn (Mask $mask) => $mask
                                ->numeric()
                                ->decimalPlaces(2)
                                ->decimalSeparator('.')
                                ->thousandsSeparator(',')
                                ->mapToDecimalSeparator(['.'])
                                ->padFractionalZeros()
                                ->normalizeZeros(true)
                        ])
                        ->pattern('money')
                    ),


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
                Tables\Columns\TextColumn::make('enpro_doc')->label('enpro')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at'),
                // Tables\Columns\TextColumn::make('id')->label('Doc Code'),
               TextColumn::make('customer.first_name')->searchable(),
               TextColumn::make('customer.phone')->searchable()->label('phone'),

            //    TextColumn::make('bill.address1')->label('billing'),
               TextColumn::make('items_sum_quantity')
                ->label('Quantity')
                ->sum('items','quantity'),
                Tables\Columns\TextColumn::make('total_price')->label('item price'),
                Tables\Columns\TextColumn::make('discount_amount')->label('discount'),
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
