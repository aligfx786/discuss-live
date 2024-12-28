<?php

namespace App\Enums;

// User statuses and roles
enum UserStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Suspended = 'suspended'; // Temporary block
    case Banned = 'banned';      // Permanent block

    // public function label(): string
    // {
    //     return match ($this) {
    //         self::Active => 'Active Account',
    //         self::Inactive => 'Inactive Account',
    //         self::Suspended => 'Suspended Account',
    //         self::Banned => 'Banned Account'
    //     };
    // }

//     public function description(): string
//     {
//         return match ($this) {
//             self::Active => 'User has full access to the platform',
//             self::Inactive => 'User account is temporarily deactivated',
//             self::Suspended => 'Account is suspended due to violations',
//             self::Banned => 'Account is permanently banned'
//         };
//     }
}
