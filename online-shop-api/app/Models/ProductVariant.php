<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class ProductVariant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'sku',
        'price', // Can override base product price
        'quantity',
        'image_url', // Variant specific image
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    // --- Relationships ---

    /**
     * Get the base product this variant belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get all attribute values associated with this variant (e.g., "Red", "Large").
     */
    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'attribute_value_product_variant')
                    ->withTimestamps(); // If your pivot table has timestamps
    }

    // --- Accessors & Mutators ---

    /**
     * Get the effective price for the variant.
     * If variant has its own price, use that; otherwise, use the base product's effective price.
     */
    public function getEffectivePriceAttribute(): float
    {
        return $this->price !== null ? (float)$this->price : (float)$this->product->effective_price;
    }

    /**
     * Get a descriptive name for the variant, often including its attributes.
     * Example: "T-Shirt - Red / Large"
     */
    public function getDisplayNameAttribute(): string
    {
        $name = $this->product->name;
        $options = $this->attributeValues->map(function ($value) {
            return $value->attributeType->name . ': ' . $value->value;
        })->implode(', ');

        return $options ? "{$name} ({$options})" : $name;
    }

    /**
     * Get the stock quantity. If using a more complex inventory system, this might change.
     */
    public function getStockQuantityAttribute(): int
    {
        return (int)$this->quantity;
    }
}
