<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Scheduled Reports
 *
 * The ScheduledReports API lets you manage scheduled email reports, as well as
 * generate, download or email any existing report.
 */
trait ScheduledReports
{
    /**
     * Add a scheduled report.
     *
     * @throws InvalidRequestException
     */
    public function addReport(
        string $description,
        string $period,
        string $hour,
        string $reportType,
        string $reportFormat,
        array $reports,
        string $parameters,
        string $idSegment = '',
        array $optional = []
    ): mixed
    {
        return $this->request('ScheduledReports.addReport', [
            'description' => $description,
            'period' => $period,
            'hour' => $hour,
            'reportType' => $reportType,
            'reportFormat' => $reportFormat,
            'reports' => $reports,
            'parameters' => $parameters,
            'idSegment' => $idSegment,
        ], $optional);
    }

    /**
     * Update a scheduled report.
     *
     * @throws InvalidRequestException
     */
    public function updateReport(
        int $idReport,
        string $description,
        string $period,
        string $hour,
        string $reportType,
        string $reportFormat,
        array $reports,
        string $parameters,
        string $idSegment = '',
        array $optional = []
    ): mixed
    {
        return $this->request('ScheduledReports.updateReport', [
            'idReport' => $idReport,
            'description' => $description,
            'period' => $period,
            'hour' => $hour,
            'reportType' => $reportType,
            'reportFormat' => $reportFormat,
            'reports' => $reports,
            'parameters' => $parameters,
            'idSegment' => $idSegment,
        ], $optional);
    }

    /**
     * Delete a scheduled report.
     *
     * @throws InvalidRequestException
     */
    public function deleteReport(int $idReport, array $optional = []): mixed
    {
        return $this->request('ScheduledReports.deleteReport', [
            'idReport' => $idReport,
        ], $optional);
    }

    /**
     * Get a list of scheduled reports.
     *
     * @throws InvalidRequestException
     */
    public function getReports(
        string $idReport = '',
        string $ifSuperUserReturnOnlySuperUserReports = '',
        string $idSegment = '',
        array $optional = []
    ): mixed
    {
        return $this->request('ScheduledReports.getReports', [
            'idReport' => $idReport,
            'ifSuperUserReturnOnlySuperUserReports' => $ifSuperUserReturnOnlySuperUserReports,
            'idSegment' => $idSegment,
        ], $optional);
    }

    /**
     * Generate a scheduled report.
     *
     * @throws InvalidRequestException
     */
    public function generateReport(
        int $idReport,
        string $language = '',
        string $outputType = '',
        string $reportFormat = '',
        string $parameters = '',
        array $optional = []
    ): mixed
    {
        return $this->request('ScheduledReports.generateReport', [
            'idReport' => $idReport,
            'language' => $language,
            'outputType' => $outputType,
            'reportFormat' => $reportFormat,
            'parameters' => $parameters,
        ], $optional);
    }

    /**
     * Send a scheduled report.
     *
     * @throws InvalidRequestException
     */
    public function sendReport(int $idReport, string $force = '', array $optional = []): mixed
    {
        return $this->request('ScheduledReports.sendReport', [
            'idReport' => $idReport,
            'force' => $force,
        ], $optional);
    }
}
