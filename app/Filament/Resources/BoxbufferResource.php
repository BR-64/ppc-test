<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BoxbufferResource\Pages;
use App\Filament\Resources\BoxbufferResource\RelationManagers;
use App\Models\BoxBuffer;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BoxbufferResource extends Resource
{
    protected static ?string $model = Boxbuffer::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('ship_to')->disabled(),
                Forms\Components\TextInput::make('buffer_percent'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ship_to')->disabled(),
                Tables\Columns\TextColumn::make('buffer_percent')->disabled(),
                // Tables\Columns\TextInputColumn::make('buffer_percent')
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
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBoxbuffers::route('/'),
            'create' => Pages\CreateBoxbuffer::route('/create'),
            'edit' => Pages\EditBoxbuffer::route('/{record}/edit'),
        ];
    }    
}
