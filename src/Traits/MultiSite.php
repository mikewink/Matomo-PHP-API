<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Multi Sites
 *
 * The MultiSites API lets you request the key metrics (visits, page views,
 * revenue) for all Websites in Matomo.
 */
trait MultiSite
{
    /**
     * Get information about multiple sites.
     *
     * @throws InvalidRequestException
     */
    public function getMultiSites(string $segment = '', string $enhanced = '', array $optional = []): bool|object
    {
        return $this->_request('MultiSites.getAll', [
            'segment' => $segment,
            'enhanced' => $enhanced,
        ], $optional);
    }

    /**
     * Get key metrics about one of the sites the user manages.
     *
     * @throws InvalidRequestException
     */
    public function getOne(string $segment = '', string $enhanced = '', array $optional = []): bool|object
    {
        return $this->_request('MultiSites.getOne', [
            'segment' => $segment,
            'enhanced' => $enhanced,
        ], $optional);
    }
}
