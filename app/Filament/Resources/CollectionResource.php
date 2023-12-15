<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CollectionResource\Pages;
use App\Filament\Resources\CollectionResource\RelationManagers;
use App\Filament\Resources\CollectionResource\RelationManagers\ProductsRelationManager;
use App\Models\pCollection;
use App\Models\Collection;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CollectionResource extends Resource
{
    protected static ?string $model = pCollection::class;
    protected static ?string $navigationLabel = 'Collections';
    protected static ?string $navigationGroup = 'Product Management';
    protected static ?int $navigationSort = 2;


    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Collection Info')->schema([
                    TextInput::make('id')->label('ID')->disabled(),
                    TextInput::make('collection_name'),
                    TextInput::make('brand_name'),
                    Toggle::make('published')
                    ])->columns(3),
                    FileUpload::make('image')
                    ->image(),
                    FileUpload::make('coll_image')->label('collection images')
                    ->preserveFilenames()
                    ->image()
                    ->multiple(),
                    Textarea::make('description'),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->width(60)->height(120),
                TextColumn::make('collection_name'),
                TextColumn::make('brand_name'),
                TextColumn::make('products_count')
                // pCollection::withCount('products')
                    ->counts('products')
                    ->label('no. of products'),
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
            ProductsRelationManager::class
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCollections::route('/'),
            'create' => Pages\CreateCollection::route('/create'),
            'edit' => Pages\EditCollection::route('/{record}/edit'),
        ];
    }    

    protected static function getNavigationBadge(): ?string{
        return self::getModel()::count();
    }
}
