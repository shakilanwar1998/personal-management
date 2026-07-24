<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LendingResource\Pages;
use App\Filament\Resources\LendingResource\RelationManagers;
use App\Models\LendingTransaction;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class LendingResource extends Resource
{
    protected static ?string $model = LendingTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-up-right';

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
                Tables\Columns\TextColumn::make('returned_amount')
                    ->label('Returned')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('outstanding')
                    ->label('Outstanding')
                    ->numeric()
                    ->state(fn (LendingTransaction $record) => $record->outstanding),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Returned' => 'success',
                        'Partially Returned' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('return')
                    ->color('success')
                    ->label('Return')
                    ->button()
                    ->form([
                        TextInput::make('amount')
                            ->label('Return amount')
                            ->numeric()
                            ->required()
                            ->minValue(0.01)
                            ->default(fn (LendingTransaction $record) => $record->outstanding)
                            ->maxValue(fn (LendingTransaction $record) => $record->outstanding)
                            ->helperText(fn (LendingTransaction $record) => 'Outstanding: ' . number_format($record->outstanding, 2)),
                    ])
                    ->action(function (LendingTransaction $record, array $data) {
                        $record->returned_amount = (float) $record->returned_amount + (float) $data['amount'];

                        if ((float) $record->returned_amount >= (float) $record->amount) {
                            $record->is_returned = true;
                        }

                        $record->save();

                        Notification::make()
                            ->success()
                            ->title($record->is_returned ? 'Loan fully returned' : 'Partial return recorded')
                            ->send();
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
