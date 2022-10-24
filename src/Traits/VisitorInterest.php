<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Visitor Interest
 *
 * VisitorInterest API lets you access two Visitor Engagement reports: number
 * of visits per number of pages, and number of visits per visit duration.
 */
trait VisitorInterest
{
    /**
     * Get the number of visits per visit duration.
     *
     * @throws InvalidRequestException
     */
    public function getNumberOfVisitsPerDuration(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('VisitorInterest.getNumberOfVisitsPerVisitDuration', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get the number of visits per visited page.
     *
     * @throws InvalidRequestException
     */
    public function getNumberOfVisitsPerPage(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('VisitorInterest.getNumberOfVisitsPerPage', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get the number of days elapsed since the last visit.
     *
     * @throws InvalidRequestException
     */
    public function getNumberOfVisitsByDaySinceLast(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('VisitorInterest.getNumberOfVisitsByDaysSinceLast', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get the number of visits by visit count.
     *
     * @throws InvalidRequestException
     */
    public function getNumberOfVisitsByCount(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('VisitorInterest.getNumberOfVisitsByVisitCount', [
            'segment' => $segment,
        ], $optional);
    }

}
