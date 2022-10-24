<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Visits Summary
 *
 * VisitsSummary API lets you access the core web analytics metrics (visits,
 * unique visitors, count of actions (page views & downloads & clicks on
 * outlinks), time on site, bounces and converted visits.
 */
trait VisitsSummary
{
    /**
     * Get a visit summary.
     *
     * @throws InvalidRequestException
     */
    public function getVisitsSummary(string $segment = '', string $columns = '', array $optional = []): object|bool
    {
        return $this->_request('VisitsSummary.get', [
            'segment' => $segment,
            'columns' => $columns,
        ], $optional);
    }

    /**
     * Get all visits.
     *
     * @throws InvalidRequestException
     */
    public function getVisits(string $segment = '', array $optional = []): int|object|bool
    {
        return $this->_request('VisitsSummary.getVisits', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get unique visits.
     *
     * @throws InvalidRequestException
     */
    public function getUniqueVisitors(string $segment = '', array $optional = []): int|object|bool
    {
        return $this->_request('VisitsSummary.getUniqueVisitors', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get user visit summary.
     *
     * @throws InvalidRequestException
     */
    public function getUserVisitors(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('VisitsSummary.getUsers', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get actions.
     *
     * @throws InvalidRequestException
     */
    public function getActions(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('VisitsSummary.getActions', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get maximum actions.
     *
     * @throws InvalidRequestException
     */
    public function getMaxActions(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('VisitsSummary.getMaxActions', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get bounce count.
     *
     * @throws InvalidRequestException
     */
    public function getBounceCount(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('VisitsSummary.getBounceCount', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get converted visits.
     *
     * @throws InvalidRequestException
     */
    public function getVisitsConverted(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('VisitsSummary.getVisitsConverted', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get the sum of all visit lengths.
     *
     * @throws InvalidRequestException
     */
    public function getSumVisitsLength(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('VisitsSummary.getSumVisitsLength', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get the sum of all visit lengths formatted in the current language.
     *
     * @throws InvalidRequestException
     */
    public function getSumVisitsLengthPretty(string $segment = '', array $optional = []): string|object|bool
    {
        return $this->_request('VisitsSummary.getSumVisitsLengthPretty', [
            'segment' => $segment,
        ], $optional);
    }
}
