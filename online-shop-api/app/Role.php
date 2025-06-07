<?php

namespace App;

enum Role: string
{
    case USER = 'user';
    case ADMIN = 'admin';
    public function label(): string
    {
        return match ($this) {
            static::USER => 'User',
            static::ADMIN => 'Administrator',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
