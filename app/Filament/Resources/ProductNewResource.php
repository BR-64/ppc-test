<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductNewResource\Pages;
use App\Filament\Resources\ProductNewResource\RelationManagers;
use App\Models\ProductNew;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductNewResource extends Resource
{
    protected static ?string $model = ProductNew::class;
    protected static ?string $navigationLabel = 'New Products';

    protected static ?string $navigationGroup = 'Product Management';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->width(80)->height(40),
                Tables\Columns\TextColumn::make('item_code')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('stock')->sortable(),
                Tables\Columns\TextColumn::make('type')->sortable(),
                Tables\Columns\TextColumn::make('color')->sortable(),
                Tables\Columns\TextColumn::make('retail_price')->sortable(),                    
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListProductNews::route('/'),
            // 'create' => Pages\CreateProductNew::route('/create'),
            'edit' => Pages\EditProductNew::route('/{record}/edit'),
        ];
    }    

    protected static function getNavigationBadge(): ?string{
        return self::getModel()::count();
    }

}
