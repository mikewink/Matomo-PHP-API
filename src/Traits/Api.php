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
     * Get current Matomo version.
     *
     * @throws InvalidRequestException
     */
    public function getMatomoVersion(array $optional = []): string
    {
        return $this->request(
            method: 'API.getMatomoVersion',
            optional: $optional
        );
    }

    /**
     * Get current IP address from the server executing this script.
     *
     * @throws InvalidRequestException
     */
    public function getIpFromHeader(array $optional = []): mixed
    {
        return $this->request(
            method: 'API.getIpFromHeader',
            optional: $optional
        );
    }

    /**
     * Get current settings.
     *
     * @throws InvalidRequestException
     */
    public function getSettings(array $optional = []): mixed
    {
        return $this->request(
            method: 'API.getSettings',
            optional: $optional
        );
    }

    /**
     * Get default metric translations.
     *
     * @throws InvalidRequestException
     */
    public function getDefaultMetricTranslations(array $optional = []): mixed
    {
        return $this->request(
            method: 'API.getDefaultMetricTranslations',
            optional: $optional
        );
    }

    /**
     * Get default metrics.
     *
     * @throws InvalidRequestException
     */
    public function getDefaultMetrics(array $optional = []): mixed
    {
        return $this->request(
            method: 'API.getDefaultMetrics',
            optional: $optional
        );
    }

    /**
     * Get default processed metrics.

     * @throws InvalidRequestException
     */
    public function getDefaultProcessedMetrics(array $optional = []): mixed
    {
        return $this->request('API.getDefaultProcessedMetrics', [], $optional);
    }

    /**
     * Get default metrics documentation.
     *
     * @throws InvalidRequestException
     */
    public function getDefaultMetricsDocumentation(array $optional = []): mixed
    {
        return $this->request('API.getDefaultMetricsDocumentation', [], $optional);
    }

    /**
     * Get default metric translations.
     *
     * @param  array  $sites  Array with the ID's of the sites
     *
     * @throws \VisualAppeal\InvalidRequestException
     */
    public function getSegmentsMetadata(array $sites = [], array $optional = []): mixed
    {
        return $this->request('API.getSegmentsMetadata', [
            'idSites' => $sites
        ], $optional);
    }

    /**
     * Get the URL of the logo.
     *
     * @param  bool  $pathOnly Return the url (false) or the absolute path (true)
     *
     * @throws InvalidRequestException
     */
    public function getLogoUrl(bool $pathOnly = false, array $optional = []): mixed
    {
        return $this->request('API.getLogoUrl', [
            'pathOnly' => $pathOnly
        ], $optional);
    }

    /**
     * Get the URL of the header logo.
     *
     * @param  bool  $pathOnly Return the url (false) or the absolute path (true)
     *
     * @throws InvalidRequestException
     */
    public function getHeaderLogoUrl(bool $pathOnly = false, array $optional = []): mixed
    {
        return $this->request('API.getHeaderLogoUrl', [
            'pathOnly' => $pathOnly
        ], $optional);
    }

    /**
     * Get metadata from the API.
     *
     * @throws InvalidRequestException
     */
    public function getMetadata(string $apiModule, string $apiAction, array $apiParameters = [], array $optional = []): mixed
    {
        return $this->request('API.getMetadata', [
            'apiModule' => $apiModule,
            'apiAction' => $apiAction,
            'apiParameters' => $apiParameters,
        ], $optional);
    }

    /**
     * Get metadata from a report.
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
    ): mixed
    {
        return $this->request('API.getReportMetadata', [
            'idSites' => $idSites,
            'hideMetricsDoc' => $hideMetricsDoc,
            'showSubtableReports' => $showSubtableReports,
        ], $optional);
    }

    /**
     * Get processed report.
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
    ): mixed
    {
        return $this->request('API.getProcessedReport', [
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
     * Get API.
     *
     * @throws InvalidRequestException
     */
    public function getApi(string $segment = '', string $columns = '', array $optional = []): mixed
    {
        return $this->request('API.get', [
            'segment' => $segment,
            'columns' => $columns,
        ], $optional);
    }

    /**
     * Get row evolution.
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
    ): mixed
    {
        return $this->request('API.getRowEvolution', [
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
     *
     * Takes array of the API methods as an argument to send together.
     * For example, ['API.get', 'Action.get', 'DevicesDetection.getType']
     *
     * @throws InvalidRequestException
     */
    public function getBulkRequest(array $methods = [], array $optional = []): mixed
    {
        $urls = [];

        foreach ($methods as $key => $method) {
            $urls['urls[' . $key . ']'] = urlencode('method=' . $method);
        }

        return $this->request('API.getBulkRequest', $urls, $optional);
    }

    /**
     * Get a list of available widgets.
     *
     * @throws InvalidRequestException
     */
    public function getWidgetMetadata(): mixed
    {
        return $this->request('API.getWidgetMetadata');
    }

    /**
     * Get a list of all available pages that exist including the widgets they
     * include.
     *
     * @throws InvalidRequestException
     */
    public function getReportPagesMetadata(): mixed
    {
        return $this->request('API.getReportPagesMetadata');
    }

    /**
     * Get suggested values for segments.
     *
     * @throws InvalidRequestException
     */
    public function getSuggestedValuesForSegment(string $segmentName, array $optional = []): mixed
    {
        return $this->request('API.getSuggestedValuesForSegment', [
            'segmentName' => $segmentName,
        ], $optional);
    }
}
