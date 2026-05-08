<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentMethod: string
{
    case Cod = 'cod';
    case SslCommerz = 'sslcommerz';
    case Stripe = 'stripe';
    case Bkash = 'bkash';

    public function label(): string
    {
        return match($this) {
            self::Cod => 'Cash on Delivery',
            self::SslCommerz => 'SSLCommerz',
            self::Stripe => 'Stripe',
            self::Bkash => 'bKash',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Cod => 'gray',
            self::SslCommerz => 'primary',
            self::Stripe => 'info',
            self::Bkash => 'danger',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn (self $case) => [$case->value => $case->label()]
        )->toArray();
    }
}
