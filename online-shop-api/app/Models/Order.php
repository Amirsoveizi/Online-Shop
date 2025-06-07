<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal_amount',
        'discount_amount',
        'tax_amount',
        'shipping_amount',
        'total_amount',
        'payment_method',
        'payment_status',
        'transaction_id',
        'shipping_name', 'shipping_address_line1', 'shipping_address_line2', 'shipping_city',
        'shipping_state_province', 'shipping_postal_code', 'shipping_country', 'shipping_phone', 'shipping_email',
        'billing_name', 'billing_address_line1', 'billing_address_line2', 'billing_city',
        'billing_state_province', 'billing_postal_code', 'billing_country', 'billing_phone', 'billing_email',
        'customer_notes',
        'admin_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => OrderStatus::class,
        'payment_status' => PaymentStatus::class,
        'subtotal_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * The "booted" method of the model.
     *
     * Used here to generate a unique order number.
     */
    protected static function booted(): void
    {
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                // Generate a unique order number, e.g., ORD-YYYYMMDD-XXXXX
                $prefix = 'ORD-';
                $datePart = now()->format('Ymd');
                // Ensure uniqueness (simple approach, might need retry for high concurrency)
                $lastOrder = static::where('order_number', 'like', $prefix . $datePart . '-%')
                                   ->orderBy('order_number', 'desc')
                                   ->first();
                $nextId = $lastOrder ? (int)substr(strrchr($lastOrder->order_number, '-'), 1) + 1 : 1;
                $order->order_number = $prefix . $datePart . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
            }
        });
    }


    // --- Relationships ---

    /**
     * Get the user that placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all items in the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // --- Accessors & Mutators ---

    /**
     * Get the full shipping address as a formatted string.
     */
    public function getFormattedShippingAddressAttribute(): string
    {
        $lines = [];
        if ($this->shipping_name) $lines[] = $this->shipping_name;
        if ($this->shipping_address_line1) $lines[] = $this->shipping_address_line1;
        if ($this->shipping_address_line2) $lines[] = $this->shipping_address_line2;
        if ($this->shipping_city || $this->shipping_state_province || $this->shipping_postal_code) {
             $lines[] = trim("{$this->shipping_city}, {$this->shipping_state_province} {$this->shipping_postal_code}");
        }
        if ($this->shipping_country) $lines[] = $this->shipping_country;
        if ($this->shipping_phone) $lines[] = "Phone: {$this->shipping_phone}";
        if ($this->shipping_email) $lines[] = "Email: {$this->shipping_email}";
        return implode("\n", array_filter($lines));
    }

    /**
     * Get the full billing address as a formatted string.
     */
    public function getFormattedBillingAddressAttribute(): string
    {
        $lines = [];
        if ($this->billing_name) $lines[] = $this->billing_name;
        if ($this->billing_address_line1) $lines[] = $this->billing_address_line1;
        if ($this->billing_address_line2) $lines[] = $this->billing_address_line2;
        if ($this->billing_city || $this->billing_state_province || $this->billing_postal_code) {
            $lines[] = trim("{$this->billing_city}, {$this->billing_state_province} {$this->billing_postal_code}");
        }
        if ($this->billing_country) $lines[] = $this->billing_country;
        if ($this->billing_phone) $lines[] = "Phone: {$this->billing_phone}";
        if ($this->billing_email) $lines[] = "Email: {$this->billing_email}";
        return implode("\n", array_filter($lines));
    }
}
