<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Dashboard
 *
 * The Dashboard API gives information about dashboards.
 */
trait Dashboard
{
    /**
     * Get list of dashboards.
     *
     * @throws InvalidRequestException
     */
    public function getDashboards(array $optional = []): object|bool
    {
        return $this->_request('Dashboard.getDashboards', [], $optional);
    }
}
