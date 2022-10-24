<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Devices Detection
 *
 * The DevicesDetection API lets you access reports on your visitors devices,
 * brands, models, operating system, browsers.
 */
trait DevicesDetection
{
    /**
     * Get a device type.
     *
     * @throws InvalidRequestException
     */
    public function getDeviceType(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('DevicesDetection.getType', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get a device brand.
     *
     * @throws InvalidRequestException
     */
    public function getDeviceBrand(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('DevicesDetection.getBrand', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get a device model.
     *
     * @throws InvalidRequestException
     */
    public function getDeviceModel(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('DevicesDetection.getModel', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get operating system families.
     *
     * @throws InvalidRequestException
     */
    public function getOSFamilies(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('DevicesDetection.getOsFamilies', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get OS versions.
     *
     * @throws InvalidRequestException
     */
    public function getOsVersions(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('DevicesDetection.getOsVersions', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get browsers.
     *
     * @throws InvalidRequestException
     */
    public function getBrowsers(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('DevicesDetection.getBrowsers', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get browser versions.
     *
     * @throws InvalidRequestException
     */
    public function getBrowserVersions(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('DevicesDetection.getBrowserVersions', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get browser engines.
     *
     * @throws InvalidRequestException
     */
    public function getBrowserEngines(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('DevicesDetection.getBrowserEngines', [
            'segment' => $segment,
        ], $optional);
    }
}
