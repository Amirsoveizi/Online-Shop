<?php

namespace App;

enum OrderStatus: string
{
    // Common Order Statuses
    case PENDING = 'pending';           // Order received, awaiting payment or processing
    case PROCESSING = 'processing';     // Payment received (or confirmed), order is being prepared
    case SHIPPED = 'shipped';           // Order has been handed over to the courier
    case DELIVERED = 'delivered';       // Order has been successfully delivered to the customer
    case COMPLETED = 'completed';       // Order delivered and no further action needed (often same as delivered)
    case CANCELLED = 'cancelled';       // Order was cancelled by customer or admin
    case REFUNDED = 'refunded';         // Order (or part of it) has been refunded
    case FAILED = 'failed';             // Order failed (e.g., payment failure, inventory issue before processing)
    case ON_HOLD = 'on_hold';           // Order requires manual verification or is temporarily paused
    case PARTIALLY_SHIPPED = 'partially_shipped'; // If an order can be split into multiple shipments
    case AWAITING_PAYMENT = 'awaiting_payment';   // For payment methods that are not instant (e.g., bank transfer)

    /**
     * Get a human-readable label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PROCESSING => 'Processing',
            self::SHIPPED => 'Shipped',
            self::DELIVERED => 'Delivered',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
            self::REFUNDED => 'Refunded',
            self::FAILED => 'Failed',
            self::ON_HOLD => 'On Hold',
            self::PARTIALLY_SHIPPED => 'Partially Shipped',
            self::AWAITING_PAYMENT => 'Awaiting Payment',
            default => ucfirst($this->value),
        };
    }

    /**
     * Get an array of all case values.
     * Useful for validation rules (e.g., Rule::in(OrderStatus::values()))
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Check if the order is in a final state (no further processing).
     */
    public function isFinal(): bool
    {
        return in_array($this, [
            self::DELIVERED,
            self::COMPLETED,
            self::CANCELLED,
            self::REFUNDED,
            self::FAILED,
        ]);
    }

    /**
     * Check if the order can be cancelled by a customer.
     */
    public function canBeCancelledByUser(): bool
    {
        return in_array($this, [
            self::PENDING,
            self::AWAITING_PAYMENT,
            // self::PROCESSING, // Depending on your business logic
        ]);
    }
}
