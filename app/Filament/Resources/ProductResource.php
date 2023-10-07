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
    // protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        // $category = att_category::pluck('category_name');

        // print_r($category);

        // $enproImg= pProduct::query()->get('image');
        // print($enproImg);
        // $this->getRouteName();
 
        return 
        $form
            ->schema([
                ViewField::make('enpro image')->view('filament.components.Image'),
                // ViewField::make('image')->view('image'),
                FileUpload::make('webimage')
                ->image()
                ->multiple(),
                Forms\Components\TextInput::make('item_code')->disabled(),

                Forms\Components\Fieldset::make('Website Info')->schema([
                    Forms\Components\TextInput::make('form'),
                    Forms\Components\TextInput::make('glaze'),
                    Forms\Components\TextInput::make('BZ'),
                    Forms\Components\TextInput::make('technique'),
                    Forms\Components\TextInput::make('collection')->disabled(),
                    // Select::make('collection')
                    //     ->relationship('collection', 'collection_name')
                    //     ->preload()
                    //     ->searchable(),
                    Forms\Components\TextInput::make('category')->disabled(),
                    //  Select::make('category')
                    //     ->relationship('category', 'category_name')
                    //     ->preload()
                    //     ->searchable(),
                    Forms\Components\TextInput::make('type'),
                    Forms\Components\TextInput::make('brand_name'),
                    Forms\Components\TextInput::make('color'),
                    Forms\Components\TextInput::make('finish'),     
                    // Select::make('category')
                    //     ->options(
                    //         $category
                    //     ),                  
                    ])->columns(3),

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

                
                    
                    Forms\Components\Fieldset::make('Setting')->schema([
                        Toggle::make('published'),
                        Toggle::make('newp'),
                        Toggle::make('Highlight'),
                        Toggle::make('pre_order'),
                        ])->columns(4),
                        //
                    // TagsInput::make('tags')
                    ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // ImageColumn::make('webimage')->width(80)->height(40),
                ImageColumn::make('image')->width(80)->height(40),
                Tables\Columns\TextColumn::make('item_code')->sortable()->searchable(),
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
