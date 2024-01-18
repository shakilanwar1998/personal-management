<?php

namespace App\Filament\Resources\LendingResource\Pages;

use App\Filament\Resources\LendingResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class EditLending extends EditRecord
{
    protected static string $resource = LendingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')->required(),
                Forms\Components\TextInput::make('borrower_name')->required(),
                Forms\Components\TextInput::make('amount')->numeric()->required(),
                Forms\Components\DatePicker::make('due_date')->nullable(),
                Forms\Components\Toggle::make('is_returned')
                    ->label('Returned')
                    ->required(),
            ]);
    }
}
