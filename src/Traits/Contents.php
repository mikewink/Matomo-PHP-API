<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Contents
 *
 * API for plugin Contents.
 */
trait Contents
{
    /**
     * Get content names.
     *
     * @throws InvalidRequestException
     */
    public function getContentNames(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Contents.getContentNames', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get content pieces.
     *
     * @throws InvalidRequestException
     */
    public function getContentPieces(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Contents.getContentPieces', [
            'segment' => $segment,
        ], $optional);
    }

}
