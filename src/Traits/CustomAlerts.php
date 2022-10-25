<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Custom Alerts
 */
trait CustomAlerts
{
    /**
     * Get alert details.
     *
     * @throws InvalidRequestException
     */
    public function getAlert(int $idAlert, array $optional = []): mixed
    {
        return $this->request('CustomAlerts.getAlert', [
            'idAlert' => $idAlert,
        ], $optional);
    }

    /**
     * Get values for alerts in the past.
     *
     * @throws InvalidRequestException
     */
    public function getValuesForAlertInPast(int $idAlert, string $subPeriodN, array $optional = []): mixed
    {
        return $this->request('CustomAlerts.getValuesForAlertInPast', [
            'idAlert' => $idAlert,
            'subPeriodN' => $subPeriodN,
        ], $optional);
    }

    /**
     * Get all alert details.
     *
     * @param  string  $idSites Comma separated list of site IDs
     *
     * @throws InvalidRequestException
     */
    public function getAlerts(string $idSites, string $ifSuperUserReturnAllAlerts = '', array $optional = []): mixed
    {
        return $this->request('CustomAlerts.getAlerts', [
            'idSites' => $idSites,
            'ifSuperUserReturnAllAlerts' => $ifSuperUserReturnAllAlerts,
        ], $optional);
    }

    /**
     * Add an alert.
     *
     * @param  array  $idSites Array of site IDs
     *
     * @throws InvalidRequestException
     */
    public function addAlert(
        string $name,
        array $idSites,
        int $emailMe,
        string $additionalEmails,
        string $phoneNumbers,
        string $metric,
        string $metricCondition,
        string $metricValue,
        string $comparedTo,
        string $reportUniqueId,
        string $reportCondition = '',
        string $reportValue = '',
        array $optional = []
    ): mixed
    {
        return $this->request('CustomAlerts.addAlert', [
            'name' => $name,
            'idSites' => $idSites,
            'emailMe' => $emailMe,
            'additionalEmails' => $additionalEmails,
            'phoneNumbers' => $phoneNumbers,
            'metric' => $metric,
            'metricCondition' => $metricCondition,
            'metricValue' => $metricValue,
            'comparedTo' => $comparedTo,
            'reportUniqueId' => $reportUniqueId,
            'reportCondition' => $reportCondition,
            'reportValue' => $reportValue,
        ], $optional);
    }

    /**
     * Edit an alert.
     *
     * @param  array  $idSites Array of site IDs
     *
     * @throws InvalidRequestException
     */
    public function editAlert(
        int $idAlert,
        string $name,
        array $idSites,
        int $emailMe,
        string $additionalEmails,
        string $phoneNumbers,
        string $metric,
        string $metricCondition,
        string $metricValue,
        string $comparedTo,
        string $reportUniqueId,
        string $reportCondition = '',
        string $reportValue = '',
        array $optional = []
    ): mixed {
        return $this->request('CustomAlerts.editAlert', [
            'idAlert' => $idAlert,
            'name' => $name,
            'idSites' => $idSites,
            'emailMe' => $emailMe,
            'additionalEmails' => $additionalEmails,
            'phoneNumbers' => $phoneNumbers,
            'metric' => $metric,
            'metricCondition' => $metricCondition,
            'metricValue' => $metricValue,
            'comparedTo' => $comparedTo,
            'reportUniqueId' => $reportUniqueId,
            'reportCondition' => $reportCondition,
            'reportValue' => $reportValue,
        ], $optional);
    }

    /**
     * Delete an alert
     *
     * @throws InvalidRequestException
     */
    public function deleteAlert(int $idAlert, array $optional = []): mixed
    {
        return $this->request('CustomAlerts.deleteAlert', [
            'idAlert' => $idAlert,
        ], $optional);
    }

    /**
     * Get triggered alerts.
     *
     * @throws InvalidRequestException
     */
    public function getTriggeredAlerts(array $idSites, array $optional = []): mixed
    {
        return $this->request('CustomAlerts.getTriggeredAlerts', [
            'idSites' => $idSites,
        ], $optional);
    }

}
