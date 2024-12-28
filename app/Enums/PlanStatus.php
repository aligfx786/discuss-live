<?php

namespace App\Enums;

enum PlanStatus: string
{
    case INACTIVE = 'inactive';    // Initial state - no subscription activity
    case PENDING = 'pending';      // Awaiting payment/activation
    case TRIAL = 'trial';          // In trial period
    case ACTIVE = 'active';        // Paid and active
    case CANCELLED = 'cancelled';  // Cancelled but may still be usable
    case EXPIRED = 'expired';      // Past end date
    case FAILED = 'failed';        // Payment failed

    //     public function isUsable(): bool
    //     {
    //         return match ($this) {
    //             self::TRIAL, self::ACTIVE => true,
    //             self::CANCELLED => true, // Might depend on your grace period logic
    //             default => false
    //         };
    //     }
    
    //     public function canTransitionTo(self $newStatus): bool
    //     {
    //         return match ($this) {
    //             self::INACTIVE => [self::PENDING, self::TRIAL, self::ACTIVE],
    //             self::PENDING => [self::TRIAL, self::ACTIVE, self::FAILED],
    //             self::TRIAL => [self::ACTIVE, self::EXPIRED],
    //             self::ACTIVE => [self::CANCELLED, self::EXPIRED],
    //             self::CANCELLED => [self::EXPIRED],
    //             default => []
    //         };
    //     }
}
