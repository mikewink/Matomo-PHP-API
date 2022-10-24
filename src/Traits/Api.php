<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Metadata API
 *
 * The Metadata API gives information about all other available APIs methods,
 * as well as providing human-readable and more complete outputs than normal
 * API methods.
 */
trait Api
{
    /**
     * Get current Matomo version
     * @throws InvalidRequestException
     */
    public function getMatomoVersion(array $optional = []): string|bool
    {
        return $this->_request('API.getMatomoVersion', [], $optional);
    }

    /**
     * Get current ip address (from the server executing this script)
     *
     * @throws InvalidRequestException
     */
    public function getIpFromHeader(array $optional = []): bool|object
    {
        return $this->_request('API.getIpFromHeader', [], $optional);
    }

    /**
     * Get current settings
     *
     * @throws InvalidRequestException
     */
    public function getSettings(array $optional = []): bool|object
    {
        return $this->_request('API.getSettings', [], $optional);
    }

    /**
     * Get default metric translations
     *
     * @throws InvalidRequestException
     */
    public function getDefaultMetricTranslations(array $optional = []): bool|object
    {
        return $this->_request('API.getDefaultMetricTranslations', [], $optional);
    }

    /**
     * Get default metrics
     *
     * @throws InvalidRequestException
     */
    public function getDefaultMetrics(array $optional = []): bool|object
    {
        return $this->_request('API.getDefaultMetrics', [], $optional);
    }

    /**
     * Get default processed metrics

     * @throws InvalidRequestException
     */
    public function getDefaultProcessedMetrics(array $optional = []): bool|object
    {
        return $this->_request('API.getDefaultProcessedMetrics', [], $optional);
    }

    /**
     * Get default metrics documentation

     * @throws InvalidRequestException
     */
    public function getDefaultMetricsDocumentation(array $optional = []): bool|object
    {
        return $this->_request('API.getDefaultMetricsDocumentation', [], $optional);
    }

    /**
     * Get default metric translations
     *
     * @param  array  $sites  Array with the ID's of the sites
     *
     * @throws \VisualAppeal\InvalidRequestException
     */
    public function getSegmentsMetadata(array $sites = [], array $optional = []): bool|object
    {
        return $this->_request('API.getSegmentsMetadata', [
            'idSites' => $sites
        ], $optional);
    }

    /**
     * Get the url of the logo
     *
     * @param  bool  $pathOnly Return the url (false) or the absolute path (true)
     *
     * @throws InvalidRequestException
     */
    public function getLogoUrl(bool $pathOnly = false, array $optional = []): bool|object
    {
        return $this->_request('API.getLogoUrl', [
            'pathOnly' => $pathOnly
        ], $optional);
    }

    /**
     * Get the url of the header logo
     *
     * @param  bool  $pathOnly Return the url (false) or the absolute path (true)
     *
     * @throws InvalidRequestException
     */
    public function getHeaderLogoUrl(bool $pathOnly = false, array $optional = []): bool|object
    {
        return $this->_request('API.getHeaderLogoUrl', [
            'pathOnly' => $pathOnly
        ], $optional);
    }

    /**
     * Get metadata from the API
     *
     * @param  string  $apiModule Module
     * @param  string  $apiAction Action
     * @param  array  $apiParameters Parameters
     *
     * @throws InvalidRequestException
     */
    public function getMetadata(string $apiModule, string $apiAction, array $apiParameters = [], array $optional = []): bool|object
    {
        return $this->_request('API.getMetadata', [
            'apiModule' => $apiModule,
            'apiAction' => $apiAction,
            'apiParameters' => $apiParameters,
        ], $optional);
    }

    /**
     * Get metadata from a report
     *
     * @param array $idSites Array with the ID's of the sites
     *
     * @throws InvalidRequestException
     */
    public function getReportMetadata(
        array $idSites,
        string $hideMetricsDoc = '',
        string $showSubtableReports = '',
        array $optional = []
    ): bool|object
    {
        return $this->_request('API.getReportMetadata', [
            'idSites' => $idSites,
            'hideMetricsDoc' => $hideMetricsDoc,
            'showSubtableReports' => $showSubtableReports,
        ], $optional);
    }

    /**
     * Get processed report
     *
     * @param  string  $apiModule Module
     * @param  string  $apiAction Action
     *
     * @throws InvalidRequestException
     */
    public function getProcessedReport(
        string $apiModule,
        string $apiAction,
        string $segment = '',
        string $apiParameters = '',
        int|string $idGoal = '',
        bool|string $showTimer = '1',
        string $hideMetricsDoc = '',
        array $optional = []
    ): bool|object
    {
        return $this->_request('API.getProcessedReport', [
            'apiModule' => $apiModule,
            'apiAction' => $apiAction,
            'segment' => $segment,
            'apiParameters' => $apiParameters,
            'idGoal' => $idGoal,
            'showTimer' => $showTimer,
            'hideMetricsDoc' => $hideMetricsDoc,
        ], $optional);
    }

    /**
     * Get Api
     *
     * @throws InvalidRequestException
     */
    public function getApi(string $segment = '', string $columns = '', array $optional = []): bool|object
    {
        return $this->_request('API.get', [
            'segment' => $segment,
            'columns' => $columns,
        ], $optional);
    }

    /**
     * Get row evolution
     *
     * @throws \VisualAppeal\InvalidRequestException
     */
    public function getRowEvolution(
        string $apiModule,
        string $apiAction,
        string $segment = '',
        string $column = '',
        string $idGoal = '',
        string $legendAppendMetric = '1',
        string $labelUseAbsoluteUrl = '1',
        array $optional = []
    ): bool|object
    {
        return $this->_request('API.getRowEvolution', [
            'apiModule' => $apiModule,
            'apiAction' => $apiAction,
            'segment' => $segment,
            'column' => $column,
            'idGoal' => $idGoal,
            'legendAppendMetric' => $legendAppendMetric,
            'labelUseAbsoluteUrl' => $labelUseAbsoluteUrl,
        ], $optional);
    }

    /**
     * Get the result of multiple requests bundled together.
     * Takes array of the API methods as an argument to send together.
     * For example, ['API.get', 'Action.get', 'DevicesDetection.getType']
     *
     * @throws InvalidRequestException
     */
    public function getBulkRequest(array $methods = [], array $optional = []): bool|object
    {
        $urls = [];

        foreach ($methods as $key => $method) {
            $urls['urls[' . $key . ']'] = urlencode('method=' . $method);
        }

        return $this->_request('API.getBulkRequest', $urls, $optional);
    }

    /**
     * Get a list of available widgets.
     *
     * @throws InvalidRequestException
     */
    public function getWidgetMetadata(): object|bool
    {
        return $this->_request('API.getWidgetMetadata');
    }

    /**
     * Get a list of all available pages that exist including the widgets they include.
     *
     * @throws InvalidRequestException
     */
    public function getReportPagesMetadata(): object|bool
    {
        return $this->_request('API.getReportPagesMetadata');
    }

    /**
     * Get suggested values for segments
     *
     *
     * @throws InvalidRequestException
     */
    public function getSuggestedValuesForSegment(string $segmentName, array $optional = []): bool|object
    {
        return $this->_request('API.getSuggestedValuesForSegment', [
            'segmentName' => $segmentName,
        ], $optional);
    }

}
