<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests'
    ]);

    $rectorConfig->phpVersion(PhpVersion::PHP_81);    

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81,
        SetList::TYPE_DECLARATION_STRICT,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::DEAD_CODE,
    ]);
};
