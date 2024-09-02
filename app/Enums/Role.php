<?php

namespace App\Enums;

enum Role: int
{
    case USER = 1;
    case JUDGE = 2;
    case LAWYER = 3;
    case EMPLOYEE = 4;
    case ADMIN = 5;

    public static function getRole(int $value): string
    {
        return match ($value) {
            self::USER->value => __('Role.1'),
            self::JUDGE->value => __('Role.2'),
            self::LAWYER->value => __('Role.3'),
            self::EMPLOYEE->value => __('Role.4'),
            self::ADMIN->value => __('Role.5'),
            default => __('status.unknown'),
        };
    }

    public static function values(): array
    {
        return array_map(fn($role) => $role->value, self::cases());
    }
}