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
                Tables\Columns\IconColumn::make('is_lifetime')
                    ->label('Lifetime')
                    ->boolean(),
                Tables\Columns\TextColumn::make('return_date')
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
                    ->button()
                    ->before(function (Action $action, Investment $record) {
                        $formData = $action->getFormData();

                        if($formData['amount'] > $record->amount){
                            Income::create([
                                'date' => date('Y-m-d'),
                                'amount' => $formData['amount'] - $record->amount,
                                'remarks' => 'Profit from '.$record->company_name
                            ]);
                        }elseif ($record->amount > $formData['amount']){
                            // Find or create a "Investment Loss" category
                            $lossCategory = \App\Models\ExpenseCategory::firstOrCreate(
                                ['name' => 'Investment Loss'],
                                ['parent' => 0, 'is_stats' => true]
                            );
                            
                            Expense::create([
                                'date' => date('Y-m-d'),
                                'amount' => $record->amount - $formData['amount'],
                                'remarks' => 'Loss from Investment of '.$record->company_name,
                                'category_id' => $lossCategory->id
                            ]);
                        }
                    })
                    ->form([
                        TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->default(function ($record){
                                return $record->amount;
                            })
                    ])
                    ->action(function (Investment $record) {
                        $record->is_returned = !$record->is_returned;
                        $record->save();
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
