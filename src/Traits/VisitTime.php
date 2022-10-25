<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Visit Time
 *
 * VisitTime API lets you access reports by Hour (Server time), and by Hour
 * Local Time of your visitors.
 */
trait VisitTime
{
    /**
     * Get the visit by local time
     *
     *
     * @throws InvalidRequestException
     */
    public function getVisitLocalTime(string $segment = '', array $optional = []): mixed
    {
        return $this->request('VisitTime.getVisitInformationPerLocalTime', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get the visit by server time
     *
     * @param  string  $hideFutureHoursWhenToday Hide the future hours when the report is created for today
     *
     * @throws InvalidRequestException
     */
    public function getVisitServerTime(string $segment = '', string $hideFutureHoursWhenToday = '', array $optional = []): mixed
    {
        return $this->request('VisitTime.getVisitInformationPerServerTime', [
            'segment' => $segment,
            'hideFutureHoursWhenToday' => $hideFutureHoursWhenToday,
        ], $optional);
    }

    /**
     * Get the visit by server time
     *
     *
     * @throws InvalidRequestException
     */
    public function getByDayOfWeek(string $segment = '', array $optional = []): mixed
    {
        return $this->request('VisitTime.getByDayOfWeek', [
            'segment' => $segment,
        ], $optional);
    }

}
