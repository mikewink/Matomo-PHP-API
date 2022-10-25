<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Insights
 *
 * API for plugin Insights.
 */
trait Insights
{
    /**
     * Check if Matomo can generate insights for current period.
     *
     * @throws InvalidRequestException
     */
    public function canGenerateInsights(array $optional = []): mixed
    {
        return $this->request('Insights.canGenerateInsights', [], $optional);
    }

    /**
     * Get insights overview.
     *
     * @throws InvalidRequestException
     */
    public function getInsightsOverview(string $segment, array $optional = []): mixed
    {
        return $this->request('Insights.getInsightsOverview', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get movers and shakers overview.
     *
     * @throws InvalidRequestException
     */
    public function getMoversAndShakersOverview(string $segment, array $optional = []): mixed
    {
        return $this->request('Insights.getMoversAndShakersOverview', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get movers and shakers.
     *
     * @throws InvalidRequestException
     */
    public function getMoversAndShakers(
        int $reportUniqueId,
        string $segment,
        int $comparedToXPeriods = 1,
        int $limitIncreaser = 4,
        int $limitDecreaser = 4,
        array $optional = []
    ): mixed
    {
        return $this->request('Insights.getMoversAndShakers', [
            'reportUniqueId' => $reportUniqueId,
            'segment' => $segment,
            'comparedToXPeriods' => $comparedToXPeriods,
            'limitIncreaser' => $limitIncreaser,
            'limitDecreaser' => $limitDecreaser,
        ], $optional);
    }

    /**
     * Get insights.
     *
     * @param  int  $minImpactPercent (0-100)
     * @param  int  $minGrowthPercent (0-100)
     *
     * @throws InvalidRequestException
     */
    public function getInsights(
        int $reportUniqueId,
        string $segment,
        int $limitIncreaser = 5,
        int $limitDecreaser = 5,
        string $filterBy = '',
        int $minImpactPercent = 2,
        int $minGrowthPercent = 20,
        int $comparedToXPeriods = 1,
        string $orderBy = 'absolute',
        array $optional = []
    ): mixed
    {
        return $this->request('Insights.getInsights', [
            'reportUniqueId' => $reportUniqueId,
            'segment' => $segment,
            'limitIncreaser' => $limitIncreaser,
            'limitDecreaser' => $limitDecreaser,
            'filterBy' => $filterBy,
            'minImpactPercent' => $minImpactPercent,
            'minGrowthPercent' => $minGrowthPercent,
            'comparedToXPeriods' => $comparedToXPeriods,
            'orderBy' => $orderBy,
        ], $optional);
    }
}
