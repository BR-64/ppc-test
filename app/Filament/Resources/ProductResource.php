<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\pProduct;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = pProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                ViewField::make('')->view('filament.components.Image'),
                Forms\Components\Fieldset::make('Info from Enpro')->schema([
                    Forms\Components\TextInput::make('item_code'),
                    Forms\Components\TextInput::make('type'),
                    Forms\Components\TextInput::make('color'),
                    Forms\Components\TextInput::make('finish'),
                    Forms\Components\TextInput::make('weight'),
                    Forms\Components\TextInput::make('price'),

                ])->columns(2)->disabled(),
                Forms\Components\Fieldset::make('Website Info')->schema([
                    Toggle::make('published'),
                    Toggle::make('newp'),
                    Toggle::make('highlight'),

                ])->columns(2)
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->width(80)->height(40),
                Tables\Columns\TextColumn::make('item_code'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('color'),
                Tables\Columns\TextColumn::make('retail_price'),
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
}
