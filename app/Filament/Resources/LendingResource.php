<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LendingResource\Pages;
use App\Filament\Resources\LendingResource\RelationManagers;
use App\Models\LendingTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class LendingResource extends Resource
{
    protected static ?string $model = LendingTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Loans';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')->required(),
                Forms\Components\TextInput::make('borrower_name')->required(),
                Forms\Components\TextInput::make('amount')->numeric()->required(),
                Forms\Components\DatePicker::make('due_date')->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('borrower_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_returned')
                    ->label('Returned')
                    ->boolean()
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('Returned')
                    ->color('success')
                    ->label('Return')
                    ->action(function (LendingTransaction $record) {
                        $record->is_returned = !$record->is_returned;
                        $record->save();
                    })->hidden(function ($record){
                        return $record->is_returned;
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListLendings::route('/'),
            'create' => Pages\CreateLending::route('/create'),
            'edit' => Pages\EditLending::route('/{record}/edit'),
        ];
    }
}
