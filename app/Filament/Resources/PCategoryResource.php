<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PCategoryResource\Pages;
use App\Filament\Resources\PCategoryResource\RelationManagers;
use App\Models\Category;
use App\Models\PCategory;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PCategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('image')
                ->directory('img')
                ->preserveFilenames()
                ->image(),
                Forms\Components\TextInput::make('label'),
                Forms\Components\TextInput::make('name')->label('category as in database'),

                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->width(80)->height(40),
                Tables\Columns\TextColumn::make('label')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('name')->label('category as in database')->sortable()->searchable(),
                Tables\Columns\ToggleColumn::make('published')->sortable(),
                Tables\Columns\TextInputColumn::make('col_order')->sortable()->searchable(),


                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->requiresConfirmation(),
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
            'index' => Pages\ListPCategories::route('/'),
            'create' => Pages\CreatePCategory::route('/create'),
            'edit' => Pages\EditPCategory::route('/{record}/edit'),
        ];
    }    
}
