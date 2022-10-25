<?php

declare(strict_types=1);

namespace VisualAppeal\Enums;

enum Period: string
{
    case DAY = 'day';
    case WEEK = 'week';
    case MONTH = 'month';
    case YEAR = 'year';
    case RANGE = 'range';
}
