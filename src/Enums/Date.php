<?php

declare(strict_types=1);

namespace VisualAppeal\Enums;

enum Date: string
{
    case TODAY = 'today';
    case YESTERDAY = 'yesterday';
    case LAST_WEEK = 'lastWeek';
    case LAST_MONTH = 'lastMonth';
    case LAST_YEAR = 'lastYear';
}
