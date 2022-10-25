<?php

declare(strict_types=1);

namespace VisualAppeal\Enums;

enum GraphStyle: string
{
    case EVOLUTION = 'evolution';
    case VERTICAL_BAR = 'verticalBar';
    case PIE = 'pie';
    case PIE_3D = '3dPie';
}
