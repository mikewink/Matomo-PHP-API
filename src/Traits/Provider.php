<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Provider
 *
 * Get provider information.
 *
 * @link https://github.com/matomo-org/plugin-Provider
 */
trait Provider
{
    /**
     * Get information about visitors internet providers.
     *
     * @throws InvalidRequestException
     */
    public function getProvider(string $segment = '', array $optional = []): mixed
    {
        return $this->request('Provider.getProvider', [
            'segment' => $segment,
        ], $optional);
    }
}
