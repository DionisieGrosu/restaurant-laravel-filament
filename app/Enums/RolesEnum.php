<?php

namespace App\Enums;

enum RolesEnum: string
{
    case ADMIN = 'admin';
    case CLIENT = 'client';
    case USER = 'user';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
