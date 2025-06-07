<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null'); // Product might be deleted
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->onDelete('set null'); // Variant might be deleted

            $table->string('product_name'); // Denormalized for historical accuracy
            $table->string('variant_sku')->nullable(); // Denormalized
            $table->text('variant_options')->nullable(); // Store JSON/string of options like "Color: Red, Size: M"

            $table->unsignedInteger('quantity');
            $table->decimal('price_per_unit', 10, 2); // Price at time of purchase
            $table->decimal('total_price', 12, 2); // quantity * price_per_unit
            $table->timestamps();

            $table->index('order_id');
            $table->index('product_id');
            $table->index('product_variant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
