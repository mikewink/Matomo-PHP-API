<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Overlay
 */
trait Overlay
{
    /**
     * Unknown.
     *
     * @throws InvalidRequestException
     */
    public function getOverlayTranslations(array $optional = []): mixed
    {
        return $this->request('Overlay.getTranslations', [], $optional);
    }

    /**
     * Get overlay excluded query parameters.
     *
     * @throws InvalidRequestException
     */
    public function getOverlayExcludedQueryParameters(array $optional = []): mixed
    {
        return $this->request('Overlay.getExcludedQueryParameters', [], $optional);
    }

    /**
     * Get overlay following pages.
     *
     * @throws InvalidRequestException
     */
    public function getOverlayFollowingPages(string $segment = '', array $optional = []): mixed
    {
        return $this->request('Overlay.getFollowingPages', [
            'segment' => $segment,
        ], $optional);
    }
}
