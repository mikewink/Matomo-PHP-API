<?php

declare(strict_types=1);

namespace VisualAppeal;

use Httpful\Exception\NetworkErrorException;
use Httpful\Request;
use Httpful\Response;
use InvalidArgumentException;
use VisualAppeal\Enums\Date;
use VisualAppeal\Enums\Format;
use VisualAppeal\Enums\Period;
use VisualAppeal\Traits\Actions;
use VisualAppeal\Traits\Annotations;
use VisualAppeal\Traits\Api;
use VisualAppeal\Traits\Contents;
use VisualAppeal\Traits\CustomAlerts;
use VisualAppeal\Traits\SiteManager;
use VisualAppeal\Traits\VisitsSummary;

/**
 * Matomo Reporting API
 *
 * The APIs let you programmatically request any analytics reports from Matomo,
 * for one or several websites and for any given date and period and in any
 * format (CSV, JSON, XML, etc.). Matomo also provides Management APIs to
 * create, update and delete websites, users, user privileges, custom
 * dashboards, email reports, goals, funnels, custom dimensions, alerts,
 * videos, heatmaps, session recordings, custom segments, and more.
 *
 * @see API reference: https://developer.matomo.org/api-reference/reporting-api
 * @see Code repository: https://github.com/VisualAppeal/Matomo-PHP-API
 */
class Matomo
{
    use Api;
    use Actions;
    use Annotations;
    use Contents;
    use CustomAlerts;
    use SiteManager;
    use VisitsSummary;

    final public const ERROR_EMPTY = 11;

    /*
        // Period parameter
        final public const PERIOD_DAY = 'day';
        final public const PERIOD_WEEK = 'week';
        final public const PERIOD_MONTH = 'month';
        final public const PERIOD_YEAR = 'year';
        final public const PERIOD_RANGE = 'range';

        // Date parameter
        final public const DATE_TODAY = 'today';
        final public const DATE_YESTERDAY = 'yesterday';
        final public const DATE_LAST_WEEK = 'lastWeek';
        final public const DATE_LAST_MONTH = 'lastMonth';
        final public const DATE_LAST_YEAR = 'lastYear';

        // Result format parameter
        final public const FORMAT_XML = 'xml';
        final public const FORMAT_JSON = 'json';
        final public const FORMAT_CSV = 'csv';
        final public const FORMAT_TSV = 'tsv';
        final public const FORMAT_HTML = 'html';
        final public const FORMAT_RSS = 'rss';
        final public const FORMAT_PHP = 'php';
        final public const FORMAT_ORIGINAL = 'original';
    */

    // Image Graph
    /*
    final public const GRAPH_EVOLUTION = 'evolution';
    final public const GRAPH_VERTICAL_BAR = 'verticalBar';
    final public const GRAPH_PIE = 'pie';
    final public const GRAPH_PIE_3D = '3dPie';
    */


    public Format $format;

    private int $filter_limit = 100;

    // Optional Matomo Reporting API parameter
    private string $language = 'en';

    // Matomo PHP API specific
    private int $timeout = 5;
    private bool $verifySsl = false;
    private bool $isJsonDecodeAssoc = false;

    /**
     * Create a new instance.
     *
     * @param string   $site   The URL of the Matomo installation.
     * @param string   $token  The Matomo user authentication token.
     * @param int|null $siteId The ID of the Matomo site.
     */
    public function __construct(
        private string $site,
        private string $token,
        private ?int $siteId = null,
        private ?Period $period = null,
        private null|Date|string $date = null,
        private ?string $rangeStart = '',
        private ?string $rangeEnd = null
    ) {
        $this->format = Format::JSON;
        $this->period = Period::DAY;

        if (!empty($rangeStart)) {
            $this->setRange($rangeStart, $rangeEnd);
        } else {
            $this->setDate(Date::YESTERDAY);
        }
    }

    /**
     * Set Matomo date range.
     *
     * @param string|null $rangeStart e.g. 2012-02-10 (YYYY-mm-dd) or
     *                                last5(lastX), previous12(previousY)…
     * @param string|null $rangeEnd   e.g. 2012-02-12. Leave this parameter
     *                                empty to request all data from
     *                                $rangeStart until now
     */
    public function setRange(
        string $rangeStart = null,
        string $rangeEnd = null
    ): Matomo {
        $this->date = null;
        $this->rangeStart = $rangeStart;
        $this->rangeEnd = $rangeEnd;

        if (is_null($rangeEnd)) {
            if (str_contains($rangeStart, 'last') || str_contains($rangeStart,
                    'previous')) {
                $this->setDate($rangeStart);
            } else {
                $this->rangeEnd = Date::TODAY->value;
            }
        }

        return $this;
    }

    /**
     * Set the Matomo date.
     */
    public function setDate(null|Date|string $date = null): Matomo
    {
        $this->date = $date;
        $this->rangeStart = null;
        $this->rangeEnd = null;

        return $this;
    }

    /**
     * Get the URL of the Matomo installation.
     */
    public function getSite(): string
    {
        return $this->site;
    }

    /**
     * Set the URL of the Matomo installation.
     */
    public function setSite(string $url): Matomo
    {
        $this->site = trim($url, ' \t\n\r\0\x0B/');

        return $this;
    }

    /**
     * Get the Matomo authentication token.
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Set the Matomo authentication token.
     */
    public function setToken(string $token): Matomo
    {
        $this->token = trim($token, ' \t\n\r\0\x0B');

        return $this;
    }

    /**
     * Get current Matomo site ID.
     */
    public function getSiteId(): ?int
    {
        return $this->siteId;
    }

    /**
     * Set current Matomo site ID.
     */
    public function setSiteId(?int $id = null): Matomo
    {
        $this->siteId = $id;

        return $this;
    }

    /**
     * Get the Matomo language.
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Set the Matomo language.
     */
    public function setLanguage(string $language): Matomo
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Set the Matomo time period.
     */
    public function setPeriod(Period $period): Matomo
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Get the Matomo date range, separated by a comma.
     */
    public function getRange(): string
    {
        if (empty($this->rangeEnd)) {
            return $this->rangeStart;
        }

        return $this->rangeStart.','.$this->rangeEnd;
    }

    /**
     * Get the number of rows that should be returned.
     */
    public function getFilterLimit(): int
    {
        return $this->filter_limit;
    }

    /**
     * Set the number of rows that should be returned.
     */
    public function setFilterLimit(int $filterLimit): Matomo
    {
        $this->filter_limit = $filterLimit;

        return $this;
    }

    /**
     * Checks if json_decode returns an associative array.
     */
    public function isJsonDecodeAssoc(): bool
    {
        return $this->isJsonDecodeAssoc;
    }

    /**
     * Sets the json_decode format to an associative array.
     */
    public function setIsJsonDecodeAssoc(bool $isJsonDecodeAssoc): Matomo
    {
        $this->isJsonDecodeAssoc = $isJsonDecodeAssoc;

        return $this;
    }

    /**
     * If the certificate of the Matomo installation should be verified.
     */
    public function getVerifySsl(): bool
    {
        return $this->verifySsl;
    }

    /**
     * Set if the certificate of the Matomo installation should be verified.
     */
    public function setVerifySsl(bool $verifySsl): Matomo
    {
        $this->verifySsl = $verifySsl;

        return $this;
    }

    /**
     * Get the request timeout.
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Set the request timeout.
     */
    public function setTimeout(int $timeout): Matomo
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Reset to default variables.
     */
    public function reset(): Matomo
    {
        $this->period = PERIOD::DAY->value;
        $this->date = '';
        $this->rangeStart = DATE::YESTERDAY->value;
        $this->rangeEnd = null;

        return $this;
    }

    /**
     * Make a request to the Matomo Reporting API.
     *
     * @param string      $method   Matomo Reporting API method
     * @param array       $params   Method parameters
     * @param array       $optional Optional parameters
     * @param string|null $format   Override response format
     *
     * @return mixed
     * @throws \VisualAppeal\InvalidRequestException
     */
    private function request(
        string $method,
        array $params = [],
        array $optional = [],
        string $format = null
    ): mixed {
        $params += $optional;
        $url = $this->parseUrl($method, $params);

        if ($url === '') {
            throw new InvalidRequestException('Could not parse URL!');
        }

        $req = Request::get($url);

        if ($this->verifySsl) {
            $req->enableStrictSSL();
        } else {
            $req->disableStrictSSL();
        }

        $req->withTimeout($this->timeout);

        try {
            $response = $req->send();
        } catch (NetworkErrorException $networkErrorException) {
            throw new InvalidRequestException(
                $networkErrorException->getMessage(),
                $networkErrorException->getCode(),
                $networkErrorException
            );
        }

        try {
            return $this->finishResponse(
                $this->parseResponseWithFormat($response, $format),
                $method,
                $params
            );
        } catch (InvalidResponseException $invalidResponseException) {
            throw new InvalidRequestException(
                $invalidResponseException->getMessage(),
                $invalidResponseException->getCode(),
                $invalidResponseException
            );
        }
    }

    /**
     * Create the request URL with method and parameters.
     *
     * @throws InvalidArgumentException
     */
    private function parseUrl(
        string $apiMethod,
        array $apiParams = []
    ): string {
        $defaultParams = [
                'module' => 'API',
                'method' => $apiMethod,
                'token_auth' => $this->token,
                'idSite' => $this->siteId,
                'period' => $this->getPeriod(),
                'format' => $this->getFormat(),
                'language' => $this->language,
                'filter_limit' => $this->filter_limit,
            ] + $apiParams;

        if (!empty($this->rangeStart) && !empty($this->rangeEnd)) {
            $defaultParams += [
                'date' => $this->rangeStart.','.$this->rangeEnd,
            ];
        } elseif (!empty($this->getDate())) {
            $defaultParams += [
                'date' => $this->getDate(),
            ];
        } else {
            throw new InvalidArgumentException('Specify a date or a date range!');
        }

        return $this->site.'?'.http_build_query($defaultParams);
    }

    /**
     * Get the Matomo time period.
     */
    public function getPeriod(): string
    {
        return $this->period->value;
    }

    /**
     * Get the Matomo response format.
     */
    public function getFormat(): string
    {
        return $this->format->value;
    }

    /**
     * Set the Matomo response format.*
     */
    public function setFormat(Format $format): Matomo
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get the Matomo date.
     */
    public function getDate(): string
    {
        return is_string($this->date) ? $this->date : $this->date->value;
    }

    /**
     * Validate response and return the value(s).
     *
     * @throws InvalidResponseException
     */
    private function finishResponse(
        mixed $response,
        string $apiMethod,
        array $apiParams
    ): mixed {
        $valid = $this->isValidResponse($response);

        if ($valid) {
            return $response->value ?? $response;
        }

        throw new InvalidResponseException($valid.' ('.$this->parseUrl($apiMethod,
                $apiParams).')');
    }

    /**
     * Check if the request was successful.
     */
    private function isValidResponse(mixed $response): array|bool|int|string
    {
        if (is_null($response)) {
            return self::ERROR_EMPTY;
        }

        if (!(property_exists($response, 'result') && $response->result !== null)
            || ($response->result !== 'error')) {
            return true;
        }

        if (is_array($response)) {
            return $response;
        }

        return $response->message;
    }

    /**
     * Parse the response.
     */
    private function parseResponseWithFormat(
        Response $response,
        string $overrideFormat = null
    ): mixed {
        $format = $overrideFormat ?? $this->getFormat();

        if ($format === FORMAT::JSON->value) {
            try {
                return json_decode($response->getRawBody(),
                    $this->isJsonDecodeAssoc, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                return $e->getMessage();
            }
        }

        return $response->getRawBody();
    }
}
