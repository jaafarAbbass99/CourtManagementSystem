<?php

namespace App\Enums;


enum Gender: int
{
    case MALE = 1 ;
    case FEMALE = 2 ; 


    public static function getGender(int $value): string
    {
        return match ($value) {
            self::MALE->value =>__('Gender.1'),
            self::FEMALE->value => __('Gender.2'),
            default => __('status.unknown'),
        };
    }

    public static function values(): array
    {
        return array_map(fn($gender) => $gender->value, self::cases());
    }

}


