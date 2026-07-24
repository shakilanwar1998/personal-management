<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvestmentResource\Pages;
use App\Filament\Resources\InvestmentResource\RelationManagers;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Investment;
use App\Models\LendingTransaction;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class InvestmentResource extends Resource
{
    protected static ?string $model = Investment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Business';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date'),
                Forms\Components\TextInput::make('purpose')
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->minValue(0.01)
                    ->default(0.00)
                    ->prefix('৳'),
                Forms\Components\Toggle::make('is_lifetime')
                    ->required(),
                Forms\Components\DatePicker::make('return_date'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('company_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('purpose')
                    ->searchable(),
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
                    ->state(fn (Investment $record) => $record->outstanding),
                Tables\Columns\IconColumn::make('is_lifetime')
                    ->label('Lifetime')
                    ->boolean(),
                Tables\Columns\TextColumn::make('return_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Returned' => 'success',
                        'Partially Returned' => 'warning',
                        'Lifetime' => 'info',
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
                            ->required()
                            ->numeric()
                            ->minValue(0.01)
                            ->default(fn (Investment $record) => $record->outstanding)
                            ->helperText(fn (Investment $record) => 'Invested: ' . number_format($record->rawAmount(), 2)
                                . ' | Outstanding: ' . number_format($record->outstanding, 2)
                                . '. Enter more than the outstanding principal to book a profit.'),
                        Forms\Components\Toggle::make('final')
                            ->label('Final settlement')
                            ->helperText('Close this investment now. If less than the invested principal has been returned, the remainder is written off as a loss.')
                            ->default(false),
                    ])
                    ->action(function (Investment $record, array $data) {
                        $invested = $record->rawAmount();
                        $prevReturned = $record->rawReturnedAmount();
                        $returnNow = (float) $data['amount'];
                        $newReturned = $prevReturned + $returnNow;

                        // Book profit incrementally: only the portion received above the
                        // invested principal that hasn't already been booked as profit.
                        $profitBefore = max(0, $prevReturned - $invested);
                        $profitAfter = max(0, $newReturned - $invested);
                        $profitDelta = $profitAfter - $profitBefore;

                        if ($profitDelta > 0) {
                            Income::create([
                                'date' => date('Y-m-d'),
                                'amount' => $profitDelta,
                                'remarks' => 'Profit from ' . $record->company_name,
                            ]);
                        }

                        $record->returned_amount = $newReturned;

                        $fullyRecovered = $newReturned >= $invested;
                        $finalSettlement = (bool) ($data['final'] ?? false);

                        if ($fullyRecovered) {
                            $record->is_returned = true;
                        } elseif ($finalSettlement) {
                            // Write off the remaining principal as a loss.
                            $loss = $invested - $newReturned;
                            if ($loss > 0) {
                                $lossCategory = \App\Models\ExpenseCategory::firstOrCreate(
                                    ['name' => 'Investment Loss'],
                                    ['parent' => 0, 'is_stats' => true]
                                );

                                Expense::create([
                                    'date' => date('Y-m-d'),
                                    'amount' => $loss,
                                    'remarks' => 'Loss from Investment of ' . $record->company_name,
                                    'category_id' => $lossCategory->id,
                                ]);
                            }
                            $record->is_returned = true;
                        }

                        $record->save();

                        Notification::make()
                            ->success()
                            ->title($record->is_returned ? 'Investment closed' : 'Partial return recorded')
                            ->send();
                    })->hidden(function ($record){
                        return $record->is_returned or $record->is_lifetime;
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
            'index' => Pages\ListInvestments::route('/'),
            'create' => Pages\CreateInvestment::route('/create'),
            'edit' => Pages\EditInvestment::route('/{record}/edit'),
        ];
    }
}
