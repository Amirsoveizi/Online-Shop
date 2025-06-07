<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\OrderStatus;
use App\PaymentStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // User might delete account
            $table->string('order_number')->unique();
            $table->string('status')->default(OrderStatus::PENDING->value);

            $table->decimal('subtotal_amount', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->decimal('tax_amount', 12, 2)->default(0.00);
            $table->decimal('shipping_amount', 10, 2)->default(0.00);
            $table->decimal('total_amount', 12, 2);

            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default(PaymentStatus::PENDING->value);
            $table->string('transaction_id')->nullable()->unique(); // From payment gateway

            // Denormalized Shipping Address
            $table->string('shipping_name')->nullable();
            $table->string('shipping_address_line1')->nullable();
            $table->string('shipping_address_line2')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state_province')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('shipping_email')->nullable(); // Good to have

            // Denormalized Billing Address (can be same as shipping)
            $table->string('billing_name')->nullable();
            $table->string('billing_address_line1')->nullable();
            $table->string('billing_address_line2')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state_province')->nullable();
            $table->string('billing_postal_code')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_phone')->nullable();
            $table->string('billing_email')->nullable(); // Good to have

            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('order_number');
            $table->index('status');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
