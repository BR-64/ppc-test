<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BillingAddressRelationManager extends RelationManager
{
    protected static string $relationship = 'Bill_Address';

    protected static ?string $recordTitleAttribute = 'user_id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('id')
                //     ->required()
                //     ->maxLength(255),
                    Forms\Components\TextInput::make('address1'),
                    Forms\Components\TextInput::make('address2'),
                    Forms\Components\TextInput::make('city'),
                    Forms\Components\TextInput::make('state'),
                    Forms\Components\TextInput::make('zipcode'),
                    Forms\Components\TextInput::make('country_code'),
                    // Tables\Columns\TextInputColumn::make('address2'),
                    // Tables\Columns\TextInputColumn::make('city'),
                    // Tables\Columns\TextInputColumn::make('state'),
                    // Tables\Columns\TextInputColumn::make('zipcode'),
                    // Tables\Columns\TextInputColumn::make('country_code'),
    
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Billing Id'),
                Tables\Columns\TextColumn::make('address1'),
                Tables\Columns\TextColumn::make('address2'),
                Tables\Columns\TextColumn::make('city'),
                Tables\Columns\TextColumn::make('state'),
                Tables\Columns\TextColumn::make('zipcode'),
                Tables\Columns\TextColumn::make('country_code'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
