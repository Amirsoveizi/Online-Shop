<?php

namespace App;

enum PaymentStatus: string
{
    case PENDING = 'pending';           // Payment initiated but not yet confirmed
    case PAID = 'paid';                 // Payment successfully completed
    case FAILED = 'failed';             // Payment attempt failed
    case REFUNDED = 'refunded';         // Payment has been fully refunded
    case PARTIALLY_REFUNDED = 'partially_refunded'; // Part of the payment has been refunded
    case VOIDED = 'voided';             // Payment was authorized but then cancelled before capture
    case AUTHORIZED = 'authorized';     // Payment authorized but not yet captured (common for some gateways)
    case AWAITING_CAPTURE = 'awaiting_capture'; // Same as authorized, more explicit
    case EXPIRED = 'expired';           // Payment link or authorization expired

    /**
     * Get a human-readable label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PAID => 'Paid',
            self::FAILED => 'Failed',
            self::REFUNDED => 'Refunded',
            self::PARTIALLY_REFUNDED => 'Partially Refunded',
            self::VOIDED => 'Voided',
            self::AUTHORIZED => 'Authorized',
            self::AWAITING_CAPTURE => 'Awaiting Capture',
            self::EXPIRED => 'Expired',
            default => ucfirst($this->value),
        };
    }

    /**
     * Get an array of all case values.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Check if the payment allows order processing.
     */
    public function allowsOrderProcessing(): bool
    {
        return in_array($this, [
            self::PAID,
            self::AUTHORIZED,       // Depending on your workflow, authorization might be enough
            self::AWAITING_CAPTURE, // Same as authorized
        ]);
    }
}
