<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * User ID
 *
 * API for plugin UserId. Allows to get User IDs table.
 */
trait UserId
{
    /**
     * Get userId for visitors.
     *
     * @throws InvalidRequestException
     */
    public function getUsersById(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('UserId.getUsers', [
            'segment' => $segment,
        ], $optional);
    }
}
