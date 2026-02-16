<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download')
                ->label('Download PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn () => route('invoices.download', $this->record))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Calculate totals before saving
        $items = $data['items'] ?? [];
        $subtotal = 0;
        foreach ($items as $item) {
            if (isset($item['quantity']) && isset($item['unit_price'])) {
                $subtotal += (float) $item['quantity'] * (float) $item['unit_price'];
            }
        }
        $taxRate = (float) ($data['tax_rate'] ?? 0);
        $taxAmount = $subtotal * ($taxRate / 100);
        $total = $subtotal + $taxAmount;

        $data['subtotal'] = $subtotal;
        $data['tax_amount'] = $taxAmount;
        $data['total'] = $total;

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->load('items');
        $this->record->calculateTotals();
    }
}
