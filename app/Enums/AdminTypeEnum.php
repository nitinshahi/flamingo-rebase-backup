<?php

namespace App\Enums;

enum AdminTypeEnum: string
{
    case Super = 'super';
    case Admin = 'admin';
    case Manager = 'manager';

    public function getLabel()
    {
        return match ($this) {
            self::Super => 'Super Admin',
            self::Admin => 'Administrator',
            self::Manager => 'Manager'
        };
    }

    public function getPrecedence()
    {
        return match ($this) {
            self::Super => 1,
            self::Admin => 2,
            self::Manager => 3,
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function forSelect(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
