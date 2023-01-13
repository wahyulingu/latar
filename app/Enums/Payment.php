<?php

namespace App\Enums;

enum Payment: int
{
    case required = 0;
    case paid = 1;
    case accepted = 2;
    case late = 3;
    case rejected = 4;
}
