<?php

namespace App\Enums;

enum SessionStatus: int
{
    case scheduled = 1 ;
    case completed = 2 ;
    case cancelled = 3 ;
}