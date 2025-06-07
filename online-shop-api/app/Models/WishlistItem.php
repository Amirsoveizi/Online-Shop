<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WishlistItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'wishlist_id',
        'product_id',
        'product_variant_id',
        // 'added_at' is handled by migration default or timestamps
    ];

    /**
     * Indicates if the model should be timestamped.
     * Set to false if you only have 'added_at' and it's not standard created_at/updated_at.
     * If you kept standard timestamps in migration, leave this true or remove.
     *
     * @var bool
     */
    // public $timestamps = false; // Uncomment if you don't use created_at/updated_at

    // --- Relationships ---

    /**
     * Get the wishlist this item belongs to.
     */
    public function wishlist(): BelongsTo
    {
        return $this->belongsTo(Wishlist::class);
    }

    /**
     * Get the product associated with this wishlist item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product variant associated with this wishlist item (if any).
     */
    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
