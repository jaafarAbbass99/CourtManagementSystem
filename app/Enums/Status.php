<?php

namespace App\Enums;

enum Status: int
{
    case PENDING = 1 ;
    case APPROVED = 2 ;
    case REJECTED = 3 ;

    public static function getLabel(int $value): string
    {
        return match ($value) {
            self::PENDING->value => __('status.1'),
            self::APPROVED->value => __('status.2'),
            self::REJECTED->value => __('status.3'),
            default => __('status.unknown'),
        };
    }


    public static function values(): array
    {
        return array_map(fn($status) => $status->value, self::cases());
    }
}
