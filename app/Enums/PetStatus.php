<?php
declare(strict_types=1);

namespace App\Enums;

use Illuminate\Validation\Rules\Enum as EnumRule;

/**
 *  PetStatus Enum
 */
enum PetStatus: string
{
    case AVAILABLE = 'available';
    case PENDING = 'pending';
    case SOLD = 'sold';

    /**
     * @return array
     */
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return EnumRule
     */
    public static function toRule(): EnumRule
    {
        return new EnumRule(self::class);
    }
}
