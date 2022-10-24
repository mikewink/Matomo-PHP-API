<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Visit Frequency
 *
 * VisitFrequency API lets you access a list of metrics related to Returning
 * Visitors.
 */
trait VisitFrequency
{
    /**
     * Get the visit frequency.
     *
     * @throws InvalidRequestException
     */
    public function getVisitFrequency(string $segment = '', string $columns = '', array $optional = []): object|bool
    {
        return $this->_request('VisitFrequency.get', [
            'segment' => $segment,
            'columns' => $columns,
        ], $optional);
    }

}
