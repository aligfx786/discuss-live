<?php

namespace App\Enums;

// app/Enums/RoleStatus.php
enum RoleStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Deprecated = 'deprecated';
    case Restricted = 'restricted';

    // public function label(): string
    // {
    //     return match ($this) {
    //         self::Active => 'Active Role',
    //         self::Inactive => 'Inactive Role',
    //         self::Deprecated => 'Deprecated Role',
    //         self::Restricted => 'Restricted Role'
    //     };
    // }
}
