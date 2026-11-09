<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'name', 'email', 'address', 'city', 'state', 'postal_code', 'country', 'tax_id', 'currency', 'notes',
    ];

    /** Client profile field => invoice column it fills. */
    public const INVOICE_FIELDS = [
        'name' => 'client_name',
        'email' => 'client_email',
        'address' => 'client_address',
        'city' => 'client_city',
        'state' => 'client_state',
        'postal_code' => 'client_postal_code',
        'country' => 'client_country',
        'tax_id' => 'client_tax_id',
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /** Build profile attributes from an invoice's client_* fields. */
    public static function attributesFromInvoice(array $data): array
    {
        $attrs = [];
        foreach (self::INVOICE_FIELDS as $field => $column) {
            $attrs[$field] = $data[$column] ?? null;
        }
        $attrs['currency'] = $data['currency'] ?? 'USD';

        return $attrs;
    }
}
