<?php

namespace App\Enums;

enum PersonsEnum: int
{
    case ONE = 1;
    case TWO = 2;
    case THREE = 3;
    case FOUR = 4;
    case FIVE = 5;
    case SIX = 6;
    case SEVEN = 7;
    case EIGHT = 8;
    case NINE = 9;
    case TEN = 10;
    case MORE_THEN_TEN = 11;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function to_array(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->value] = $case->value;
        }
        return $array;
    }

    public static function get_random(): int
    {
        return rand(self::ONE->value, self::MORE_THEN_TEN->value);
    }
}
