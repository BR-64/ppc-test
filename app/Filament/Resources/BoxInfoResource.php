<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BoxInfoResource\Pages;
use App\Filament\Resources\BoxInfoResource\RelationManagers;
use App\Models\BoxInfo;
use Filament\Forms;
use Filament\Forms\Components\Actions\Modal\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BoxInfoResource extends Resource
{
    protected static ?string $model = boxinfo::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('size')->disabled(),
                TextInput::make('weight'),
                TextInput::make('cubic'),
                TextInput::make('shipcost_v1')->label('Shipcost'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('size')->sortable(),
                Tables\Columns\TextColumn::make('weight')       ->sortable()
                    ->formatStateUsing(fn (string $state): string => number_format(__("{$state}"))),
                Tables\Columns\TextColumn::make('cubic')       ->sortable()
                    ->formatStateUsing(fn (string $state): string => number_format(__("{$state}"))),
                Tables\Columns\TextColumn::make('shipcost_v1')       ->sortable()
                    ->formatStateUsing(fn (string $state): string => number_format(__("{$state}"))),
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
            'index' => Pages\ListBoxInfos::route('/'),
            'create' => Pages\CreateBoxInfo::route('/create'),
            'edit' => Pages\EditBoxInfo::route('/{record}/edit'),
        ];
    }    
}
