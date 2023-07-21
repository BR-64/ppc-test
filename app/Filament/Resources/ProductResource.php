<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\att_category;
use App\Models\pProduct;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = pProduct::class;

    protected static ?string $navigationLabel = 'Products';
    protected static ?string $navigationGroup = 'Product Management';
    protected static ?int $navigationSort = 1;


    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        // $category = att_category::pluck('category_name','category');

        // print_r($category);

        return $form
            ->schema([
                ViewField::make('')->view('filament.components.Image'),
                Forms\Components\TextInput::make('item_code')->disabled(),
                Forms\Components\Fieldset::make('Info from Enpro')->schema([

                    Forms\Components\TextInput::make('width'),
                    Forms\Components\TextInput::make('length'),
                    Forms\Components\TextInput::make('height'),
                    Forms\Components\TextInput::make('weight'),
                    Forms\Components\TextInput::make('retail_price'),

                ])->columns(4)->disabled(),
                Forms\Components\Fieldset::make('Calculated')->schema([

                    Forms\Components\TextInput::make('wlh'),
                    Forms\Components\TextInput::make('cubic_width'),
                    Forms\Components\TextInput::make('cubic_length'),
                    Forms\Components\TextInput::make('cubic_height'),
                    Forms\Components\TextInput::make('cubic_cm'),
                ])->columns(5)->disabled(),

                Forms\Components\Fieldset::make('Website Info')->schema([
                    Select::make('collection')
                        ->relationship('collection', 'collection_name')
                        ->preload()
                        ->searchable(),
                    Select::make('category')
                        ->relationship('category', 'category_name')
                        ->preload()
                        ->searchable(),
                    // Select::make('category')
                    //     ->options(
                    //         $category
                    //     ),
                    Select::make('type')
                        ->options([

                        ]),
                    Select::make('color')
                        ->options([

                        ]),
                    Select::make('finish')
                        ->options([

                        ]),
                    // Forms\Components\TextInput::make('type'),
                    // Forms\Components\TextInput::make('color'),
                    // Forms\Components\TextInput::make('finish'),
                    
                    ])->columns(3),
                    
                    Forms\Components\Fieldset::make('Setting')->schema([
                        Toggle::make('published'),
                        Toggle::make('newp'),
                        Toggle::make('highlight'),
                        Toggle::make('pre_order'),
                        ])->columns(4),
                        //
                    TagsInput::make('tags')
                    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->width(80)->height(40),
                Tables\Columns\TextColumn::make('item_code')->sortable(),
                Tables\Columns\TextColumn::make('stock.stock')->sortable(),
                Tables\Columns\TextColumn::make('type')->sortable(),
                Tables\Columns\TextColumn::make('color')->sortable(),
                Tables\Columns\TextColumn::make('retail_price')->sortable(),
                ToggleColumn::make('published')->sortable(),
                ToggleColumn::make('Highlight')->sortable()
                // Tables\Columns\TextColumn::make('item_code'),
                //
            ])
            ->filters([
                //
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
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }    

    protected static function getNavigationBadge(): ?string{
        return self::getModel()::count();
    }
}
