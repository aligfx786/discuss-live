<?php

namespace App\Enums;

enum Role: int
{
    case SUPERADMIN = 4;
    case ADMIN = 1;
    case EDITOR = 2;
    case MODERATOR = 3;
}
