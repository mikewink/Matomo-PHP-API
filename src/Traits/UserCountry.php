<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * User Country
 *
 * The UserCountry API lets you access reports about your visitors' countries
 * and continents.
 */
trait UserCountry
{
    /**
     * Get countries of all visitors.
     *
     * @throws InvalidRequestException
     */
    public function getCountry(string $segment = '', array $optional = []): mixed
    {
        return $this->request('UserCountry.getCountry', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get a list of used country codes to country names.
     *
     * @throws InvalidRequestException
     */
    public function getCountryCodeMapping(): object
    {
        return $this->request('UserCountry.getCountryCodeMapping');
    }

    /**
     * Get continents of all visitors.
     *
     * @throws InvalidRequestException
     */
    public function getContinent(string $segment = '', array $optional = []): mixed
    {
        return $this->request('UserCountry.getContinent', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get regions of all visitors.
     *
     * @throws InvalidRequestException
     */
    public function getRegion(string $segment = '', array $optional = []): mixed
    {
        return $this->request('UserCountry.getRegion', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get cities of all visitors.
     *
     * @throws InvalidRequestException
     */
    public function getCity(string $segment = '', array $optional = []): mixed
    {
        return $this->request('UserCountry.getCity', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get location from IP address.
     *
     * @throws InvalidRequestException
     */
    public function getLocationFromIP(string $ip, string $provider = '', array $optional = []): mixed
    {
        return $this->request('UserCountry.getLocationFromIP', [
            'ip' => $ip,
            'provider' => $provider,
        ], $optional);
    }

    /**
     * Get the number of distinct countries.
     *
     * @throws InvalidRequestException
     */
    public function getCountryNumber(string $segment = '', array $optional = []): mixed
    {
        return $this->request('UserCountry.getNumberOfDistinctCountries', [
            'segment' => $segment,
        ], $optional);
    }
}
