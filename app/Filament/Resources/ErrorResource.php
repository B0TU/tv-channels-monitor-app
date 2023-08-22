<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ErrorResource\Pages;
use App\Filament\Resources\ErrorResource\RelationManagers;
use App\Models\Error;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ErrorResource extends Resource
{
    protected static ?string $model = Error::class;
    protected static ?string $recordTitleAttribute = 'error_description';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('error_description')->required(),
                Forms\Components\TextInput::make('error_priority'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('error_description'),
                Tables\Columns\TextColumn::make('error_priority'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageErrors::route('/'),
        ];
    }    
}
