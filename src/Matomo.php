<?php

declare(strict_types=1);

namespace VisualAppeal;

use Httpful\Exception\NetworkErrorException;
use Httpful\Request;
use Httpful\Response;
use InvalidArgumentException;
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
 * @link Code repository: https://github.com/VisualAppeal/Matomo-PHP-API
 * @link API reference: https://developer.matomo.org/api-reference/reporting-api
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

    /**
     * @var int
     */
    final public const ERROR_EMPTY = 11;

    /**
     * @var string
     */
    final public const PERIOD_DAY = 'day';

    /**
     * @var string
     */
    final public const PERIOD_WEEK = 'week';

    /**
     * @var string
     */
    final public const PERIOD_MONTH = 'month';

    /**
     * @var string
     */
    final public const PERIOD_YEAR = 'year';

    /**
     * @var string
     */
    final public const PERIOD_RANGE = 'range';

    /**
     * @var string
     */
    final public const DATE_TODAY = 'today';

    /**
     * @var string
     */
    final public const DATE_YESTERDAY = 'yesterday';

    /**
     * @var string
     */
    final public const FORMAT_XML = 'xml';

    /**
     * @var string
     */
    final public const FORMAT_JSON = 'json';

    /**
     * @var string
     */
    final public const FORMAT_CSV = 'csv';

    /**
     * @var string
     */
    final public const FORMAT_TSV = 'tsv';

    /**
     * @var string
     */
    final public const FORMAT_HTML = 'html';

    /**
     * @var string
     */
    final public const FORMAT_RSS = 'rss';

    /**
     * @var string
     */
    final public const FORMAT_PHP = 'php';

    /**
     * @var string
     */
    final public const FORMAT_ORIGINAL = 'original';

    private string $_date = '';

    /**
     * @var int Defines the number of rows to be returned (-1: All rows).
     */
    private int $_filter_limit = 100;

    /**
     * @var string Returns data strings that can be internationalized and will be translated.
     */
    private string $_language = 'en';

    private bool $_isJsonDecodeAssoc = false;

    /**
     * @var bool If the SSL certificate of the Matomo installation should be verified.
     */
    private bool $_verifySsl = false;

    /**
     * @var int Timeout in seconds.
     */
    private int $_timeout = 5;


    // Image Graph
    final public const GRAPH_EVOLUTION = 'evolution';
    final public const GRAPH_VERTICAL_BAR = 'verticalBar';
    final public const GRAPH_PIE = 'pie';
    final public const GRAPH_PIE_3D = '3dPie';


    /**
     * Create a new instance.
     *
     * @param  string  $_site  URL of the Matomo installation
     * @param  string  $_token  API access token
     * @param  int|null  $_siteId  ID of the site
     */
    public function __construct(
        private string $_site,
        private string $_token,
        private ?int $_siteId = null,
        private string $_format = self::FORMAT_JSON,
        private string $_period = self::PERIOD_DAY,
        string $date = self::DATE_YESTERDAY,
        private ?string $_rangeStart = '',
        private ?string $_rangeEnd = null
    )
    {
        if (!empty($_rangeStart)) {
            $this->setRange($_rangeStart, $_rangeEnd);
        } else {
            $this->setDate($date);
        }
    }

    /**
     * Get the url of the Matomo installation.
     */
    public function getSite(): string
    {
        return $this->_site;
    }

    /**
     * Set the URL of the Matomo installation.
     */
    public function setSite(string $url): Matomo
    {
        $this->_site = $url;

        return $this;
    }

    /**
     * Get the Matomo authentication token.
     */
    public function getToken(): string
    {
        return $this->_token;
    }

    /**
     * Set the Matomo authentication token.
     */
    public function setToken(string $token): Matomo
    {
        $this->_token = $token;

        return $this;
    }

    /**
     * Get current Matomo site ID.
     */
    public function getSiteId(): ?int
    {
        return $this->_siteId;
    }

    /**
     * Set current Matomo site ID.
     *
     * @param  mixed|null  $id
     */
    public function setSiteId(mixed $id = null): Matomo
    {
        $this->_siteId = $id;

        return $this;
    }

    /**
     * Get the Matomo response format.
     */
    public function getFormat(): string
    {
        return $this->_format;
    }

    /**
     * Set the Matomo response format.
     *
     * @param string $format
     *        FORMAT_XML
     *        FORMAT_JSON
     *        FORMAT_CSV
     *        FORMAT_TSV
     *        FORMAT_HTML
     *        FORMAT_RSS
     *        FORMAT_PHP
     */
    public function setFormat(string $format): Matomo
    {
        $this->_format = $format;

        return $this;
    }

    /**
     * Get the Matomo language.
     */
    public function getLanguage(): string
    {
        return $this->_language;
    }

    /**
     * Set the Matomo language.
     */
    public function setLanguage(string $language): Matomo
    {
        $this->_language = $language;

        return $this;
    }

    /**
     * Get the Matomo date.
     */
    public function getDate(): string
    {
        return $this->_date;
    }

    /**
     * Set the Matomo date.
     *
     * @param string|null $date Format Y-m-d or class constant:
     *        DATE_TODAY
     *        DATE_YESTERDAY
     */
    public function setDate(string $date = null): Matomo
    {
        $this->_date = $date;
        $this->_rangeStart = null;
        $this->_rangeEnd = null;

        return $this;
    }

    /**
     * Get the Matomo time period.
     */
    public function getPeriod(): string
    {
        return $this->_period;
    }

    /**
     * Set the Matomo time period.
     *
     * @param string $period
     *        PERIOD_DAY
     *        PERIOD_MONTH
     *        PERIOD_WEEK
     *        PERIOD_YEAR
     *        PERIOD_RANGE
     */
    public function setPeriod(string $period): Matomo
    {
        $this->_period = $period;

        return $this;
    }

    /**
     * Get the Matomo, comma separated, date range.
     */
    public function getRange(): string
    {
        if (empty($this->_rangeEnd)) {
            return $this->_rangeStart;
        }

        return $this->_rangeStart . ',' . $this->_rangeEnd;
    }

    /**
     * Set Matomo date range.
     *
     * @param string|null $rangeStart e.g. 2012-02-10 (YYYY-mm-dd) or last5(lastX), previous12(previousY)...
     * @param string|null $rangeEnd e.g. 2012-02-12. Leave this parameter empty to request all data from
     *                         $rangeStart until now
     */
    public function setRange(string $rangeStart = null, string $rangeEnd = null): Matomo
    {
        $this->_date = '';
        $this->_rangeStart = $rangeStart;
        $this->_rangeEnd = $rangeEnd;

        if (is_null($rangeEnd)) {
            if (str_contains($rangeStart, 'last') || str_contains($rangeStart,
                    'previous')) {
                $this->setDate($rangeStart);
            } else {
                $this->_rangeEnd = self::DATE_TODAY;
            }
        }

        return $this;
    }

    /**
     * Get the number of rows that should be returned.
     */
    public function getFilterLimit(): int
    {
        return $this->_filter_limit;
    }

    /**
     * Set the number of rows that should be returned.
     */
    public function setFilterLimit(int $filterLimit): Matomo
    {
        $this->_filter_limit = $filterLimit;

        return $this;
    }

    /**
     * Return if JSON decode an associate array.
     */
    public function isJsonDecodeAssoc(): bool
    {
        return $this->_isJsonDecodeAssoc;
    }

    /**
     * Sets the json_decode format.
     *
     * @param bool $isJsonDecodeAssoc false decode as Object, true for decode as Associate array
     */
    public function setIsJsonDecodeAssoc(bool $isJsonDecodeAssoc): Matomo
    {
        $this->_isJsonDecodeAssoc = $isJsonDecodeAssoc;

        return $this;
    }

    /**
     * If the certificate of the Matomo installation should be verified.
     */
    public function getVerifySsl(): bool
    {
        return $this->_verifySsl;
    }

    /**
     * Set if the certificate of the Matomo installation should be verified.
     */
    public function setVerifySsl(bool $verifySsl): Matomo
    {
        $this->_verifySsl = $verifySsl;

        return $this;
    }

    public function getTimeout(): int
    {
        return $this->_timeout;
    }

    public function setTimeout(int $timeout): Matomo
    {
        $this->_timeout = $timeout;

        return $this;
    }

    /**
     * Reset all default variables.
     */
    public function reset(): Matomo
    {
        $this->_period = self::PERIOD_DAY;
        $this->_date = '';
        $this->_rangeStart = 'yesterday';
        $this->_rangeEnd = null;

        return $this;
    }

    /**
     * Make API request
     *
     * @param  string  $method  HTTP method
     * @param  array  $params  Query parameters
     * @param  array  $optional  Optional arguments for this API call
     * @param  string|null  $format  Override the response format
     *
     * @throws \VisualAppeal\InvalidRequestException
     */
    private function _request(string $method, array $params = [], array $optional = [], string $format = null): array|string|int|object|bool
    {
        $url = $this->_parseUrl($method, $params + $optional);

        if ($url === false) {
            throw new InvalidRequestException('Could not parse URL!');
        }

        $req = Request::get($url);

        if ($this->_verifySsl) {
            $req->enableStrictSSL();
        } else {
            $req->disableStrictSSL();
        }

        $req->withTimeout($this->_timeout);

        try {
            $buffer = $req->send();
        } catch (NetworkErrorException $networkErrorException) {
            throw new InvalidRequestException(
                $networkErrorException->getMessage(),
                $networkErrorException->getCode(),
                $networkErrorException
            );
        }

        try {
            return $this->_finishResponse(
                $this->_parseResponse($buffer, $format),
                $method,
                $params + $optional
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
     * Validate response and return the values.
     *
     * @throws InvalidResponseException
     */
    private function _finishResponse(mixed $response, string $method, array $params): string|int|object|bool
    {
        $valid = $this->_isValidResponse($response);

        if ($valid === true) {
            return $response->value ?? $response;
        }

        throw new InvalidResponseException($valid . ' (' . $this->_parseUrl($method, $params) . ')');
    }

    /**
     * Create request url with parameters
     *
     * @param string $method The request method
     * @param array $params Request params
     * @throws InvalidArgumentException
     */
    private function _parseUrl(string $method, array $params = []): bool|string
    {
        $params = [
                'module' => 'API',
                'method' => $method,
                'token_auth' => $this->_token,
                'idSite' => $this->_siteId,
                'period' => $this->_period,
                'format' => $this->_format,
                'language' => $this->_language,
                'filter_limit' => $this->_filter_limit
            ] + $params;

        foreach ($params as $key => $value) {
            $params[$key] = is_array($value) ? urlencode(implode(',', $value)) : urlencode((string) $value);
        }

        if (!empty($this->_rangeStart) && !empty($this->_rangeEnd)) {
            $params += [
                'date' => $this->_rangeStart.','.$this->_rangeEnd,
            ];
        } elseif (!empty($this->_date)) {
            $params += [
                'date' => $this->_date,
            ];
        } else {
            throw new InvalidArgumentException('Specify a date or a date range!');
        }

        $url = $this->_site;

        $i = 0;
        foreach ($params as $param => $val) {
            if (!empty($val)) {
                ++$i;
                if ($i > 1) {
                    $url .= '&';
                } else {
                    $url .= '?';
                }

                if (is_array($val)) {
                    $val = implode(',', $val);
                }

                $url .= $param . '=' . $val;
            }
        }

        return $url;
    }

    /**
     * Check if the request was successful.
     */
    private function _isValidResponse(mixed $response): string|bool|int
    {
        if (is_null($response)) {
            return self::ERROR_EMPTY;
        }

        if (!(property_exists($response, 'result') && $response->result !== null)
            || ($response->result !== 'error'))
        {
            return true;
        }

        return $response->message;
    }

    /**
     * Parse request result
     *
     * @param  string|null  $overrideFormat  Override the default format
     *
     * @throws \JsonException
     */
    private function _parseResponse(Response $response, string $overrideFormat = null): mixed
    {
        $format = $overrideFormat ?? $this->_format;

        return match ($format) {
            self::FORMAT_JSON => json_decode($response->getRawBody(), $this->_isJsonDecodeAssoc, 512, JSON_THROW_ON_ERROR),
            default => $response,
        };
    }
}
