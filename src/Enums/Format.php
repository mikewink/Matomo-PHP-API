<?php

declare(strict_types=1);

namespace VisualAppeal\Enums;

enum Format: string
{
    case XML = 'xml';
    case JSON = 'json';
    case CSV = 'csv';
    case TSV = 'tsv';
    case HTML = 'html';
    case RSS = 'rss';
    case PHP = 'php';
    case ORIGINAL = 'original';
}
