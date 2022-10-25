<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Resolution
 *
 * Get screen resolutions.
 */
trait Resolution
{
    /**
     * Get resolution.
     *
     * @throws InvalidRequestException
     */
    public function getResolution(string $segment = '', array $optional = []): mixed
    {
        return $this->request('Resolution.getResolution', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get configuration
     *
     * @throws InvalidRequestException
     */
    public function getConfiguration(string $segment = '', array $optional = []): mixed
    {
        return $this->request('Resolution.getConfiguration', [
            'segment' => $segment,
        ], $optional);
    }
}
