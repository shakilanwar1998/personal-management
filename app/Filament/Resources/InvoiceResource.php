<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Business';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Invoice Information')
                    ->schema([
                        Forms\Components\TextInput::make('invoice_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(fn () => (new Invoice)->generateInvoiceNumber())
                            ->disabled(fn ($context) => $context === 'edit'),
                        Forms\Components\DatePicker::make('invoice_date')
                            ->required()
                            ->default(now())
                            ->native(false),
                        Forms\Components\DatePicker::make('due_date')
                            ->native(false),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'sent' => 'Sent',
                                'paid' => 'Paid',
                                'overdue' => 'Overdue',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('draft')
                            ->required(),
                        Forms\Components\Select::make('currency')
                            ->options([
                                'USD' => 'USD - US Dollar',
                                'EUR' => 'EUR - Euro',
                                'GBP' => 'GBP - British Pound',
                            ])
                            ->default('USD')
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Client Information')
                    ->schema([
                        Forms\Components\TextInput::make('client_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('client_email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('client_address')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('client_city')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('client_state')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('client_postal_code')
                            ->maxLength(255),
                        Forms\Components\Select::make('client_country')
                            ->options([
                                'US' => 'United States',
                                'DE' => 'Germany',
                                'GB' => 'United Kingdom',
                                'CA' => 'Canada',
                                'AU' => 'Australia',
                            ])
                            ->required()
                            ->default('US')
                            ->reactive()
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('tax_rate', $state === 'DE' ? 19 : ($state === 'US' ? 0 : 0))),
                        Forms\Components\TextInput::make('client_tax_id')
                            ->label('Client Tax ID / VAT Number')
                            ->maxLength(255),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Your Business Information')
                    ->schema([
                        Forms\Components\TextInput::make('business_name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('business_email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('business_phone')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('business_website')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('business_address')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('business_city')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('business_state')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('business_postal_code')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('business_country')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('business_tax_id')
                            ->label('Your Tax ID / VAT Number')
                            ->maxLength(255),
                    ])
                    ->columns(3)
                    ->collapsible()
                    ->collapsed(),

                Forms\Components\Section::make('Invoice Items')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Textarea::make('description')
                                    ->required()
                                    ->rows(2)
                                    ->columnSpan(2)
                                    ->reactive(),
                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->minValue(0.01)
                                    ->step(0.01)
                                    ->reactive()
                                    ->afterStateUpdated(fn ($get, $set) => self::updateItemTotal($get, $set)),
                                Forms\Components\TextInput::make('unit_price')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0)
                                    ->prefix(fn ($get) => $get('../../currency') ?? 'USD')
                                    ->reactive()
                                    ->afterStateUpdated(fn ($get, $set) => self::updateItemTotal($get, $set)),
                                Forms\Components\TextInput::make('tax_rate')
                                    ->numeric()
                                    ->default(0)
                                    ->suffix('%')
                                    ->minValue(0)
                                    ->maxValue(100),
                                Forms\Components\TextInput::make('total')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->prefix(fn ($get) => $get('../../currency') ?? 'USD')
                                    ->default(fn ($get) => ($get('quantity') ?? 0) * ($get('unit_price') ?? 0)),
                            ])
                            ->columns(5)
                            ->defaultItems(1)
                            ->addActionLabel('Add Item')
                            ->reorderableWithButtons()
                            ->orderColumn('sort_order')
                            ->columnSpanFull()
                            ->live()
                            ->afterStateUpdated(fn ($get, $set) => self::calculateTotals($get, $set)),
                    ]),

                Forms\Components\Section::make('Totals')
                    ->schema([
                        Forms\Components\TextInput::make('tax_rate')
                            ->label('Tax Rate (%)')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->reactive()
                            ->afterStateUpdated(fn ($get, $set) => self::calculateTotals($get, $set)),
                        Forms\Components\TextInput::make('subtotal')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->prefix(fn ($get) => $get('currency') ?? 'USD')
                            ->reactive(),
                        Forms\Components\TextInput::make('tax_amount')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->prefix(fn ($get) => $get('currency') ?? 'USD')
                            ->reactive(),
                        Forms\Components\TextInput::make('total')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->prefix(fn ($get) => $get('currency') ?? 'USD')
                            ->reactive(),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('terms')
                            ->label('Terms & Conditions')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    protected static function updateItemTotal($get, $set): void
    {
        $quantity = (float) ($get('quantity') ?? 0);
        $unitPrice = (float) ($get('unit_price') ?? 0);
        $total = $quantity * $unitPrice;
        $set('total', number_format($total, 2, '.', ''));
    }

    protected static function calculateTotals($get, $set): void
    {
        $items = $get('items') ?? [];
        $subtotal = 0;

        foreach ($items as $item) {
            if (is_array($item)) {
                $quantity = (float) ($item['quantity'] ?? 0);
                $unitPrice = (float) ($item['unit_price'] ?? 0);
                $subtotal += $quantity * $unitPrice;
            }
        }

        $taxRate = (float) ($get('tax_rate') ?? 0);
        $taxAmount = $subtotal * ($taxRate / 100);
        $total = $subtotal + $taxAmount;

        $set('subtotal', number_format($subtotal, 2, '.', ''));
        $set('tax_amount', number_format($taxAmount, 2, '.', ''));
        $set('total', number_format($total, 2, '.', ''));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client_country')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'US' => 'success',
                        'DE' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('invoice_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'sent' => 'info',
                        'paid' => 'success',
                        'overdue' => 'danger',
                        'cancelled' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('total')
                    ->money(fn ($record) => $record->currency ?? 'USD')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('client_country')
                    ->label('Country')
                    ->options([
                        'US' => 'United States',
                        'DE' => 'Germany',
                        'GB' => 'United Kingdom',
                        'CA' => 'Canada',
                        'AU' => 'Australia',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Download PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Invoice $record) => route('invoices.download', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('invoice_date', 'desc');
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
