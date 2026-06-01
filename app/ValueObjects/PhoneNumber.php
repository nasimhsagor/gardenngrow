<?php

declare(strict_types=1);

namespace App\ValueObjects;

use InvalidArgumentException;

class PhoneNumber
{
    private string $value;

    public function __construct(string $value)
    {
        if (!self::isValid($value)) {
            throw new InvalidArgumentException(
                trans('general.phone_number_invalid', ['attribute' => trans('general.phone')])
            );
        }

        $this->value = $value;
    }

    public static function isValid(string $value): bool
    {
        return (bool) preg_match('/^(?:\+88|88)?01[3-9]\d{8}$/', $value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function toLocalFormat(): string
    {
        if (preg_match('/^(?:\+88|88)?(01[3-9]\d{8})$/', $this->value, $matches)) {
            return $matches[1];
        }
        return $this->value;
    }

    public function toInternationalFormat(): string
    {
        $local = $this->toLocalFormat();
        return '+88' . $local;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
