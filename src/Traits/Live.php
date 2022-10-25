<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Live
 *
 * The Live! API lets you access complete visit level information about your
 * visitors.
 */
trait Live
{
    /**
     * Get short information about the visit counts in the last few minutes.
     *
     * @throws InvalidRequestException
     */
    public function getCounters(int $lastMinutes = 60, string $segment = '', array $optional = []): mixed
    {
        return $this->request('Live.getCounters', [
            'lastMinutes' => $lastMinutes,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get information about the last visits.
     *
     * @throws InvalidRequestException
     * @internal param int $maxIdVisit
     * @internal param int $filterLimit
     */
    public function getLastVisitsDetails(string $segment = '', string $minTimestamp = '', string $doNotFetchActions = '', array $optional = []): mixed
    {
        return $this->request('Live.getLastVisitsDetails', [
            'segment' => $segment,
            'minTimestamp' => $minTimestamp,
            'doNotFetchActions' => $doNotFetchActions,
        ], $optional);
    }

    /**
     * Get a profile for a visitor.
     *
     * @throws InvalidRequestException
     */
    public function getVisitorProfile(string $visitorId = '', string $segment = '', array $optional = []): mixed
    {
        return $this->request('Live.getVisitorProfile', [
            'visitorId' => $visitorId,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get the ID of the most recent visitor.
     *
     * @throws InvalidRequestException
     */
    public function getMostRecentVisitorId(string $segment = '', array $optional = []): mixed
    {
        return $this->request('Live.getMostRecentVisitorId', [
            'segment' => $segment,
        ], $optional);
    }
}
