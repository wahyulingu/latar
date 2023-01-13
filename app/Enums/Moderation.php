<?php

namespace App\Enums;

enum Moderation: int
{
    case hold = 0;
    case active = 1;
    case closed = 2;
    case suspended = 3;
    case blocked = 4;
}
