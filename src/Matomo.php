<?php

declare(strict_types=1);

namespace VisualAppeal;

use Httpful\Exception\NetworkErrorException;
use Httpful\Request;
use Httpful\Response;
use InvalidArgumentException;

/**
 * Repository: https://github.com/VisualAppeal/Matomo-PHP-API
 * Matomo Reporting API reference: https://developer.matomo.org/api-reference/reporting-api
 */
class Matomo
{
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
     * @var bool If the certificate of the Matomo installation should be verified.
     */
    private bool $_verifySsl = false;

    /**
     * @var int How many redirects curl should execute until aborting.
     */
    private int $_maxRedirects = 5;

    /**
     * @var int Timeout in seconds.
     */
    private int $_timeout = 5;

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

    /**
     * How many redirects curl should execute until aborting.
     */
    public function getMaxRedirects(): int
    {
        return $this->_maxRedirects;
    }

    /**
     * Set how many redirects curl should execute until aborting.
     */
    public function setMaxRedirects(int $maxRedirects): Matomo
    {
        $this->_maxRedirects = $maxRedirects;

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
     * Requests to Matomo api
     */
    /**
     * Make API request
     *
     * @param  string  $method  HTTP method
     * @param  array  $params  Query parameters
     * @param  array  $optional  Optional arguments for this api call
     * @param  string|null  $format  Override the response format
     *
     * @throws \VisualAppeal\InvalidRequestException
     */
    private function _request(string $method, array $params = [], array $optional = [], string $format = null): object|bool
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

        // @TODO Figure out how to set max redirects
        //$req->followRedirects($this->_maxRedirects);

        $req->withTimeout($this->_timeout);

        try {
            $buffer = $req->send();
        } catch (NetworkErrorException $networkErrorException) {
            throw new InvalidRequestException($networkErrorException->getMessage(), $networkErrorException->getCode(), $networkErrorException);
        }

        try {
            return $this->_finishResponse($this->_parseResponse($buffer, $format), $method, $params + $optional);
        } catch (InvalidResponseException $invalidResponseException) {
            throw new InvalidRequestException($invalidResponseException->getMessage(), $invalidResponseException->getCode(), $invalidResponseException);
        }
    }

    /**
     * Validate request and return the values.
     *
     * @throws InvalidResponseException
     */
    private function _finishResponse(mixed $response, string $method, array $params): object|bool
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
    private function _isValidResponse(mixed $response): bool|int
    {
        if (is_null($response)) {
            return self::ERROR_EMPTY;
        }

        if (!(property_exists($response, 'result') && $response->result !== null) || ($response->result !== 'error')) {
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
            self::FORMAT_JSON => json_decode($response, $this->_isJsonDecodeAssoc, 512, JSON_THROW_ON_ERROR),
            default => $response,
        };
    }

    /**
     * MODULE: API
     * API metadata
     */
    /**
     * Get current Matomo version
     * @throws InvalidRequestException
     */
    public function getMatomoVersion(array $optional = []): bool|object
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
     * Get the result of multiple requests bundled together
     * Take as an argument an array of the API methods to send together
     * For example, ['API.get', 'Action.get', 'DeviceDetection.getType']
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

    /**
     * MODULE: ACTIONS
     * Reports for visitor actions
     */
    /**
     * Get actions
     *
     * @throws InvalidRequestException
     */
    public function getAction(string $segment = '', string $columns = '', array $optional = []): bool|object
    {
        return $this->_request('Actions.get', [
            'segment' => $segment,
            'columns' => $columns,
        ], $optional);
    }

    /**
     * Get page urls
     *
     * @throws InvalidRequestException
     */
    public function getPageUrls(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Actions.getPageUrls', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get page URLs after a site search
     *
     * @throws InvalidRequestException
     */
    public function getPageUrlsFollowingSiteSearch(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Actions.getPageUrlsFollowingSiteSearch', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get page titles after a site search
     *
     * @throws InvalidRequestException
     */
    public function getPageTitlesFollowingSiteSearch(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Actions.getPageTitlesFollowingSiteSearch', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get entry page urls
     *
     * @throws InvalidRequestException
     */
    public function getEntryPageUrls(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Actions.getEntryPageUrls', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get exit page urls
     *
     * @throws InvalidRequestException
     */
    public function getExitPageUrls(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Actions.getExitPageUrls', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get page url information
     *
     * @param  string  $pageUrl The page url
     *
     * @throws InvalidRequestException
     */
    public function getPageUrl(string $pageUrl, string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Actions.getPageUrl', [
            'pageUrl' => $pageUrl,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get page titles
     *
     * @throws InvalidRequestException
     */
    public function getPageTitles(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Actions.getPageTitles', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get entry page urls
     *
     * @throws InvalidRequestException
     */
    public function getEntryPageTitles(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Actions.getEntryPageTitles', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get exit page urls
     *
     * @throws InvalidRequestException
     */
    public function getExitPageTitles(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Actions.getExitPageTitles', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get page titles
     *
     * @param  string  $pageName The page name
     *
     * @throws InvalidRequestException
     */
    public function getPageTitle(string $pageName, string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Actions.getPageTitle', [
            'pageName' => $pageName,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get downloads
     *
     * @throws InvalidRequestException
     */
    public function getDownloads(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Actions.getDownloads', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get download information
     *
     * @param  string  $downloadUrl URL of the download
     *
     * @throws InvalidRequestException
     */
    public function getDownload(string $downloadUrl, string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Actions.getDownload', [
            'downloadUrl' => $downloadUrl,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get outlinks
     *
     * @throws InvalidRequestException
     */
    public function getOutlinks(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Actions.getOutlinks', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get outlink information
     *
     * @param  string  $outlinkUrl URL of the outlink
     *
     * @throws InvalidRequestException
     */
    public function getOutlink(string $outlinkUrl, string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Actions.getOutlink', [
            'outlinkUrl' => $outlinkUrl,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get the site search keywords
     *
     * @throws InvalidRequestException
     */
    public function getSiteSearchKeywords(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Actions.getSiteSearchKeywords', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get search keywords with no search results
     *
     * @throws InvalidRequestException
     */
    public function getSiteSearchNoResultKeywords(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Actions.getSiteSearchNoResultKeywords', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get site search categories
     *
     * @throws InvalidRequestException
     */
    public function getSiteSearchCategories(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Actions.getSiteSearchCategories', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * MODULE: ANNOTATIONS
     */
    /**
     * Add annotation
     *
     *
     * @throws InvalidRequestException
     */
    public function addAnnotation(string $note, int $starred = 0, array $optional = []): bool|object
    {
        return $this->_request('Annotations.add', [
            'note' => $note,
            'starred' => $starred,
        ], $optional);
    }

    /**
     * Save annotation
     *
     *
     * @throws InvalidRequestException
     */
    public function saveAnnotation(int $idNote, string $note = '', string $starred = '', array $optional = []): bool|object
    {
        return $this->_request('Annotations.save', [
            'idNote' => $idNote,
            'note' => $note,
            'starred' => $starred,
        ], $optional);
    }

    /**
     * Delete annotation
     *
     *
     * @throws InvalidRequestException
     */
    public function deleteAnnotation(int $idNote, array $optional = []): bool|object
    {
        return $this->_request('Annotations.delete', [
            'idNote' => $idNote,
        ], $optional);
    }

    /**
     * Delete all annotations

     * @throws InvalidRequestException
     */
    public function deleteAllAnnotations(array $optional = []): bool|object
    {
        return $this->_request('Annotations.deleteAll', [], $optional);
    }

    /**
     * Get annotation
     *
     *
     * @throws InvalidRequestException
     */
    public function getAnnotation(int $idNote, array $optional = []): bool|object
    {
        return $this->_request('Annotations.get', [
            'idNote' => $idNote,
        ], $optional);
    }

    /**
     * Get all annotations
     *
     *
     * @throws InvalidRequestException
     */
    public function getAllAnnotation(string $lastN = '', array $optional = []): bool|object
    {
        return $this->_request('Annotations.getAll', [
            'lastN' => $lastN,
        ], $optional);
    }

    /**
     * Get number of annotation for current period
     *
     *
     * @throws InvalidRequestException
     */
    public function getAnnotationCountForDates(int $lastN, string $getAnnotationText, array $optional = []): bool|object
    {
        return $this->_request('Annotations.getAnnotationCountForDates', [
            'lastN' => $lastN,
            'getAnnotationText' => $getAnnotationText
        ], $optional);
    }

    /**
     * MODULE: CONTENTS
     */
    /**
     * Get content names
     *
     * @throws InvalidRequestException
     */
    public function getContentNames(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Contents.getContentNames', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get content pieces
     *
     * @throws InvalidRequestException
     */
    public function getContentPieces(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Contents.getContentPieces', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * MODULE: CUSTOM ALERTS
     */
    /**
     * Get alert details
     *
     *
     * @throws InvalidRequestException
     */
    public function getAlert(int $idAlert, array $optional = []): bool|object
    {
        return $this->_request('CustomAlerts.getAlert', [
            'idAlert' => $idAlert,
        ], $optional);
    }

    /**
     * Get values for alerts in the past
     *
     *
     * @throws InvalidRequestException
     */
    public function getValuesForAlertInPast(int $idAlert, string $subPeriodN, array $optional = []): bool|object
    {
        return $this->_request('CustomAlerts.getValuesForAlertInPast', [
            'idAlert' => $idAlert,
            'subPeriodN' => $subPeriodN,
        ], $optional);
    }

    /**
     * Get all alert details
     *
     * @param  string  $idSites Comma separated list of site IDs
     *
     * @throws InvalidRequestException
     */
    public function getAlerts(string $idSites, string $ifSuperUserReturnAllAlerts = '', array $optional = []): bool|object
    {
        return $this->_request('CustomAlerts.getAlerts', [
            'idSites' => $idSites,
            'ifSuperUserReturnAllAlerts' => $ifSuperUserReturnAllAlerts,
        ], $optional);
    }

    /**
     * Add alert
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
    ): bool|object
    {
        return $this->_request('CustomAlerts.addAlert', [
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
     * Edit alert
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
    ): object|bool {
        return $this->_request('CustomAlerts.editAlert', [
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
     * Delete Alert
     *
     *
     * @throws InvalidRequestException
     */
    public function deleteAlert(int $idAlert, array $optional = []): object|bool
    {
        return $this->_request('CustomAlerts.deleteAlert', [
            'idAlert' => $idAlert,
        ], $optional);
    }

    /**
     * Get triggered alerts
     *
     *
     * @throws InvalidRequestException
     */
    public function getTriggeredAlerts(array $idSites, array $optional = []): object|bool
    {
        return $this->_request('CustomAlerts.getTriggeredAlerts', [
            'idSites' => $idSites,
        ], $optional);
    }

    /**
     * MODULE: Custom Dimensions
     * The Custom Dimensions API lets you manage and access reports for your configured Custom Dimensions.
     */
    /**
     * Fetch a report for the given idDimension. Only reports for active dimensions can be fetched. Requires at least
     * view access.
     *
     *
     * @throws InvalidRequestException
     */
    public function getCustomDimension(int $idDimension, array $optional = []): object|bool
    {
        return $this->_request('CustomDimensions.getCustomDimension', [
            'idDimension' => $idDimension,
        ], $optional);
    }

    /**
     * Configures a new Custom Dimension. Note that Custom Dimensions cannot be deleted, be careful when creating one
     * as you might run quickly out of available Custom Dimension slots. Requires at least Admin access for the
     * specified website. A current list of available `$scopes` can be fetched via the API method
     * `CustomDimensions.getAvailableScopes()`. This method will also contain information whether actually Custom
     * Dimension slots are available or whether they are all already in use.
     *
     * @param  string  $name The name of the dimension
     * @param  string  $scope Either 'visit' or 'action'. To get an up-to-date list of available scopes fetch the
     *                      API method `CustomDimensions.getAvailableScopes`
     * @param  int  $active '0' if dimension should be inactive, '1' if dimension should be active
     *
     * @throws InvalidRequestException
     */
    public function configureNewCustomDimension(string $name, string $scope, int $active, array $optional = []): object|bool
    {
        return $this->_request('CustomDimensions.configureNewCustomDimension', [
            'name' => $name,
            'scope' => $scope,
            'active' => $active,
        ], $optional);
    }

    /**
     * Updates an existing Custom Dimension. This method updates all values, you need to pass existing values of the
     * dimension if you do not want to reset any value. Requires at least Admin access for the specified website.
     *
     * @param  int  $idDimension The id of a Custom Dimension.
     * @param  string  $name The name of the dimension
     * @param  int  $active '0' if dimension should be inactive, '1' if dimension should be active
     *
     * @throws InvalidRequestException
     */
    public function configureExistingCustomDimension(int $idDimension, string $name, int $active, array $optional = []): object|bool
    {
        return $this->_request('CustomDimensions.configureExistingCustomDimension', [
            'idDimension' => $idDimension,
            'name' => $name,
            'active' => $active,
        ], $optional);
    }

    /**
     * @throws InvalidRequestException
     */
    public function getConfiguredCustomDimensions(): object|bool
    {
        return $this->_request('CustomDimensions.getConfiguredCustomDimensions', [
        ]);
    }

    /**
     * Get a list of all supported scopes that can be used in the API method
     * `CustomDimensions.configureNewCustomDimension`. The response also
     * contains information whether more Custom Dimensions can be created
     * or not. Requires at least Admin access for the specified website.
     *
     * @throws InvalidRequestException
     */
    public function getAvailableScopes(): object|bool
    {
        return $this->_request('CustomDimensions.getAvailableScopes', [
        ]);
    }

    /**
     * Get a list of all available dimensions that can be used in an extraction.
     * Requires at least Admin access to one website.
     *
     * @throws InvalidRequestException
     */
    public function getAvailableExtractionDimensions(): object|bool
    {
        return $this->_request('CustomDimensions.getAvailableExtractionDimensions', [
        ]);
    }

    /**
     * MODULE: CUSTOM VARIABLES
     * Custom variable information
     */
    /**
     * Get custom variables
     *
     *
     * @throws \VisualAppeal\InvalidRequestException
     */
    public function getCustomVariables(string $segment = '', array $optional = []): object|bool|array
    {
        return $this->_request('CustomVariables.getCustomVariables', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get information about a custom variable
     *
     *
     * @throws InvalidRequestException
     */
    public function getCustomVariable(int $idSubtable, string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('CustomVariables.getCustomVariablesValuesFromNameId', [
            'idSubtable' => $idSubtable,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * MODULE: DASHBOARD
     */
    /**
     * Get list of dashboards.
     *
     *
     * @throws InvalidRequestException
     */
    public function getDashboards(array $optional = []): object|bool
    {
        return $this->_request('Dashboard.getDashboards', [], $optional);
    }

    /**
     * MODULE: DEVICES DETECTION
     */
    /**
     * Get Device Type.
     *
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
     * Get Device Brand.
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
     * Get Device Model.
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
     * Get operating system families
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
     * Get os versions
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
     * Get browsers
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
     * Get browser versions
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
     * Get browser engines
     *
     * @throws InvalidRequestException
     */
    public function getBrowserEngines(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('DevicesDetection.getBrowserEngines', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * MODULE: EVENTS
     */
    /**
     * Get event categories
     *
     * @param  string  $secondaryDimension ('eventAction' or 'eventName')
     *
     * @throws InvalidRequestException
     */
    public function getEventCategory(string $segment = '', string $secondaryDimension = '', array $optional = []): bool|object
    {
        return $this->_request('Events.getCategory', [
            'segment' => $segment,
            'secondaryDimension' => $secondaryDimension,
        ], $optional);
    }

    /**
     * Get event actions
     *
     * @param  string  $secondaryDimension ('eventName' or 'eventCategory')
     *
     * @throws InvalidRequestException
     */
    public function getEventAction(string $segment = '', string $secondaryDimension = '', array $optional = []): bool|object
    {
        return $this->_request('Events.getAction', [
            'segment' => $segment,
            'secondaryDimension' => $secondaryDimension,
        ], $optional);
    }

    /**
     * Get event names
     *
     * @param  string  $secondaryDimension ('eventAction' or 'eventCategory')
     *
     * @throws InvalidRequestException
     */
    public function getEventName(string $segment = '', string $secondaryDimension = '', array $optional = []): bool|object
    {
        return $this->_request('Events.getName', [
            'segment' => $segment,
            'secondaryDimension' => $secondaryDimension,
        ], $optional);
    }

    /**
     * Get action from category ID
     *
     * @throws InvalidRequestException
     */
    public function getActionFromCategoryId(int $idSubtable, string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Events.getActionFromCategoryId', [
            'idSubtable' => $idSubtable,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get name from category ID
     *
     *
     * @throws InvalidRequestException
     */
    public function getNameFromCategoryId(int $idSubtable, string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Events.getNameFromCategoryId', [
            'idSubtable' => $idSubtable,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get category from action ID
     *
     *
     * @throws InvalidRequestException
     */
    public function getCategoryFromActionId(int $idSubtable, string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Events.getCategoryFromActionId', [
            'idSubtable' => $idSubtable,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get name from action ID
     *
     * @throws InvalidRequestException
     */
    public function getNameFromActionId(int $idSubtable, string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Events.getNameFromActionId', [
            'idSubtable' => $idSubtable,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get action from name ID
     *
     * @throws InvalidRequestException
     */
    public function getActionFromNameId(int $idSubtable, string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Events.getActionFromNameId', [
            'idSubtable' => $idSubtable,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get category from name ID
     *
     * @throws InvalidRequestException
     */
    public function getCategoryFromNameId(int $idSubtable, string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Events.getCategoryFromNameId', [
            'idSubtable' => $idSubtable,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * MODULE: EXAMPLE API
     * Get api and Matomo information
     */
    /**
     * Get the Matomo version

     * @throws InvalidRequestException
     */
    public function getExampleMatomoVersion(array $optional = []): bool|object
    {
        return $this->_request('ExampleAPI.getMatomoVersion', [], $optional);
    }

    /**
     * http://en.wikipedia.org/wiki/Phrases_from_The_Hitchhiker%27s_Guide_to_the_Galaxy#The_number_42

     * @throws InvalidRequestException
     */
    public function getExampleAnswerToLife(array $optional = []): bool|object
    {
        return $this->_request('ExampleAPI.getAnswerToLife', [], $optional);
    }

    /**
     * Unknown

     * @throws InvalidRequestException
     */
    public function getExampleObject(array $optional = []): bool|object
    {
        return $this->_request('ExampleAPI.getObject', [], $optional);
    }

    /**
     * Get the sum of the parameters
     *
     *
     * @throws InvalidRequestException
     */
    public function getExampleSum(int $a = 0, int $b = 0, array $optional = []): bool|object
    {
        return $this->_request('ExampleAPI.getSum', [
            'a' => $a,
            'b' => $b,
        ], $optional);
    }

    /**
     * Returns nothing but the success of the request

     * @throws InvalidRequestException
     */
    public function getExampleNull(array $optional = []): bool|object
    {
        return $this->_request('ExampleAPI.getNull', [], $optional);
    }

    /**
     * Get a short Matomo description

     * @throws InvalidRequestException
     */
    public function getExampleDescriptionArray(array $optional = []): bool|object
    {
        return $this->_request('ExampleAPI.getDescriptionArray', [], $optional);
    }

    /**
     * Get a short comparison with other analytic software

     * @throws InvalidRequestException
     */
    public function getExampleCompetitionDatatable(array $optional = []): bool|object
    {
        return $this->_request('ExampleAPI.getCompetitionDatatable', [], $optional);
    }

    /**
     * Get information about 42
     * http://en.wikipedia.org/wiki/Phrases_from_The_Hitchhiker%27s_Guide_to_the_Galaxy#The_number_42

     * @throws InvalidRequestException
     */
    public function getExampleMoreInformationAnswerToLife(array $optional = []): bool|object
    {
        return $this->_request('ExampleAPI.getMoreInformationAnswerToLife', [], $optional);
    }

    /**
     * Get a multidimensional array

     * @throws InvalidRequestException
     */
    public function getExampleMultiArray(array $optional = []): bool|object
    {
        return $this->_request('ExampleAPI.getMultiArray', [], $optional);
    }

    /**
     * MODULE: EXAMPLE PLUGIN
     */
    /**
     * Get a multidimensional array
     *
     *
     * @throws InvalidRequestException
     */
    public function getExamplePluginAnswerToLife(int $truth = 1, array $optional = []): bool|object
    {
        return $this->_request('ExamplePlugin.getAnswerToLife', [
            'truth' => $truth,
        ], $optional);
    }

    /**
     * Get a multidimensional array
     *
     * @throws InvalidRequestException
     */
    public function getExamplePluginReport(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('ExamplePlugin.getExampleReport', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * MODULE: FEEDBACK
     */
    /**
     * Get a multidimensional array
     *
     *
     * @throws InvalidRequestException
     */
    public function sendFeedbackForFeature(string $featureName, string $like, string $message = '', array $optional = []): bool|object
    {
        return $this->_request('Feedback.sendFeedbackForFeature', [
            'featureName' => $featureName,
            'like' => $like,
            'message' => $message,
        ], $optional);
    }

    /**
     * MODULE: GOALS
     * Handle goals
     */
    /**
     * Get all goals

     * @throws InvalidRequestException
     */
    public function getGoals(array $optional = []): bool|object
    {
        return $this->_request('Goals.getGoals', [], $optional);
    }

    /**
     * Add a goal
     *
     *
     * @throws InvalidRequestException
     */
    public function addGoal(
        string $name,
        string $matchAttribute,
        string $pattern,
        string $patternType,
        string $caseSensitive = '',
        string $revenue = '',
        string $allowMultipleConversionsPerVisit = '',
        array $optional = []
    ): bool|object
    {
        return $this->_request('Goals.addGoal', [
            'name' => $name,
            'matchAttribute' => $matchAttribute,
            'pattern' => $pattern,
            'patternType' => $patternType,
            'caseSensitive' => $caseSensitive,
            'revenue' => $revenue,
            'allowMultipleConversionsPerVisit' => $allowMultipleConversionsPerVisit,
        ], $optional);
    }

    /**
     * Update a goal
     *
     *
     * @throws InvalidRequestException
     */
    public function updateGoal(
        int $idGoal,
        string $name,
        string $matchAttribute,
        string $pattern,
        string $patternType,
        string $caseSensitive = '',
        string $revenue = '',
        string $allowMultipleConversionsPerVisit = '',
        array $optional = []
    ): bool|object
    {
        return $this->_request('Goals.updateGoal', [
            'idGoal' => $idGoal,
            'name' => $name,
            'matchAttribute' => $matchAttribute,
            'pattern' => $pattern,
            'patternType' => $patternType,
            'caseSensitive' => $caseSensitive,
            'revenue' => $revenue,
            'allowMultipleConversionsPerVisit' => $allowMultipleConversionsPerVisit,
        ], $optional);
    }

    /**
     * Delete a goal
     *
     *
     * @throws InvalidRequestException
     */
    public function deleteGoal(int $idGoal, array $optional = []): bool|object
    {
        return $this->_request('Goals.deleteGoal', [
            'idGoal' => $idGoal,
        ], $optional);
    }

    /**
     * Get the SKU of the items
     *
     *
     * @throws InvalidRequestException
     */
    public function getItemsSku(string $abandonedCarts, array $optional = []): bool|object
    {
        return $this->_request('Goals.getItemsSku', [
            'abandonedCarts' => $abandonedCarts,
        ], $optional);
    }

    /**
     * Get the name of the items
     *
     *
     * @throws InvalidRequestException
     */
    public function getItemsName(bool $abandonedCarts, array $optional = []): bool|object
    {
        return $this->_request('Goals.getItemsName', [
            'abandonedCarts' => $abandonedCarts,
        ], $optional);
    }

    /**
     * Get the categories of the items
     *
     *
     * @throws InvalidRequestException
     */
    public function getItemsCategory(bool $abandonedCarts, array $optional = []): bool|object
    {
        return $this->_request('Goals.getItemsCategory', [
            'abandonedCarts' => $abandonedCarts,
        ], $optional);
    }

    /**
     * Get conversion rates from a goal
     *
     *
     * @throws InvalidRequestException
     */
    public function getGoal(string $segment = '', string $idGoal = '', array $columns = [], array $optional = []): bool|object
    {
        return $this->_request('Goals.get', [
            'segment' => $segment,
            'idGoal' => $idGoal,
            'columns' => $columns,
        ], $optional);
    }

    /**
     * Get information about a time period, and it's conversion rates.
     *
     *
     * @throws InvalidRequestException
     */
    public function getDaysToConversion(string $segment = '', string $idGoal = '', array $optional = []): bool|object
    {
        return $this->_request('Goals.getDaysToConversion', [
            'segment' => $segment,
            'idGoal' => $idGoal,
        ], $optional);
    }

    /**
     * Get information about how many site visits create a conversion
     *
     *
     * @throws InvalidRequestException
     */
    public function getVisitsUntilConversion(string $segment = '', string $idGoal = '', array $optional = []): bool|object
    {
        return $this->_request('Goals.getVisitsUntilConversion', [
            'segment' => $segment,
            'idGoal' => $idGoal,
        ], $optional);
    }

    /**
     * MODULE: IMAGE GRAPH
     * Generate png graphs
     */

    final public const GRAPH_EVOLUTION = 'evolution';

    final public const GRAPH_VERTICAL_BAR = 'verticalBar';

    final public const GRAPH_PIE = 'pie';

    /**
     * @var string
     */
    final public const GRAPH_PIE_3D = '3dPie';

    /**
     * Generate a png report
     *
     * @param  string  $apiModule Module
     * @param  string  $apiAction Action
     * @param  string  $graphType 'evolution', 'verticalBar', 'pie' or '3dPie'
     * @param  bool|string  $aliasedGraph By default, Graphs are "smooth" (anti-aliased). If you are
     *                              generating hundreds of graphs and are concerned with performance,
     *                              you can set aliasedGraph=0. This will disable anti aliasing and
     *                              graphs will be generated faster, but look less pretty.
     * @param  array  $colors Use own colors instead of the default. The colors have to be in hexadecimal
     *                      value without '#'.

     * @throws InvalidRequestException
     */
    public function getImageGraph(
        string $apiModule,
        string $apiAction,
        string $graphType = '',
        string $outputType = '0',
        string $columns = '',
        string $labels = '',
        string $showLegend = '1',
        int|string $width = '',
        int|string $height = '',
        int|string $fontSize = '9',
        string $legendFontSize = '',
        bool|string $aliasedGraph = '1',
        string $idGoal = '',
        array $colors = [],
        array $optional = []
    ): bool|object
    {
        return $this->_request('ImageGraph.get', [
            'apiModule' => $apiModule,
            'apiAction' => $apiAction,
            'graphType' => $graphType,
            'outputType' => $outputType,
            'columns' => $columns,
            'labels' => $labels,
            'showLegend' => $showLegend,
            'width' => $width,
            'height' => $height,
            'fontSize' => $fontSize,
            'legendFontSize' => $legendFontSize,
            'aliasedGraph' => $aliasedGraph,
            'idGoal ' => $idGoal,
            'colors' => $colors,
        ], $optional, self::FORMAT_PHP);
    }

    /**
     * MODULE: LANGUAGES MANAGER
     * Get plugin insights
     */
    /**
     * Check if Matomo can generate insights for current period

     * @throws InvalidRequestException
     */
    public function canGenerateInsights(array $optional = []): bool|object
    {
        return $this->_request('Insights.canGenerateInsights', [], $optional);
    }

    /**
     * Get insights overview
     *
     * @throws InvalidRequestException
     */
    public function getInsightsOverview(string $segment, array $optional = []): bool|object
    {
        return $this->_request('Insights.getInsightsOverview', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get movers and shakers overview
     *
     * @throws InvalidRequestException
     */
    public function getMoversAndShakersOverview(string $segment, array $optional = []): bool|object
    {
        return $this->_request('Insights.getMoversAndShakersOverview', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get movers and shakers
     *
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
    ): bool|object
    {
        return $this->_request('Insights.getMoversAndShakers', [
            'reportUniqueId' => $reportUniqueId,
            'segment' => $segment,
            'comparedToXPeriods' => $comparedToXPeriods,
            'limitIncreaser' => $limitIncreaser,
            'limitDecreaser' => $limitDecreaser,
        ], $optional);
    }

    /**
     * Get insights
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
    ): bool|object
    {
        return $this->_request('Insights.getInsights', [
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

    /**
     * MODULE: LANGUAGES MANAGER
     * Manage languages
     */
    /**
     * Proof if language is available
     *
     *
     * @throws InvalidRequestException
     */
    public function getLanguageAvailable(string $languageCode, array $optional = []): bool|object
    {
        return $this->_request('LanguagesManager.isLanguageAvailable', [
            'languageCode' => $languageCode,
        ], $optional);
    }

    /**
     * Get all available languages

     * @throws InvalidRequestException
     */
    public function getAvailableLanguages(array $optional = []): bool|object
    {
        return $this->_request('LanguagesManager.getAvailableLanguages', [], $optional);
    }

    /**
     * Get all available languages with information

     * @throws InvalidRequestException
     */
    public function getAvailableLanguagesInfo(array $optional = []): bool|object
    {
        return $this->_request('LanguagesManager.getAvailableLanguagesInfo', [], $optional);
    }

    /**
     * Get all available languages with their names

     * @throws InvalidRequestException
     */
    public function getAvailableLanguageNames(array $optional = []): bool|object
    {
        return $this->_request('LanguagesManager.getAvailableLanguageNames', [], $optional);
    }

    /**
     * Get translations for a language
     *
     *
     * @throws InvalidRequestException
     */
    public function getTranslations(string $languageCode, array $optional = []): bool|object
    {
        return $this->_request('LanguagesManager.getTranslationsForLanguage', [
            'languageCode' => $languageCode,
        ], $optional);
    }

    /**
     * Get the language for the user with the login $login
     *
     *
     * @throws InvalidRequestException
     */
    public function getLanguageForUser(string $login, array $optional = []): bool|object
    {
        return $this->_request('LanguagesManager.getLanguageForUser', [
            'login' => $login,
        ], $optional);
    }

    /**
     * Set the language for the user with the login $login
     *
     *
     * @throws InvalidRequestException
     */
    public function setLanguageForUser(string $login, string $languageCode, array $optional = []): bool|object
    {
        return $this->_request('LanguagesManager.setLanguageForUser', [
            'login' => $login,
            'languageCode' => $languageCode,
        ], $optional);
    }


    /**
     * MODULE: LIVE
     * Request live data
     */
    /**
     * Get short information about the visit counts in the last few minutes.
     *
     * @param  int  $lastMinutes Default: 60
     *
     * @throws InvalidRequestException
     */
    public function getCounters(int $lastMinutes = 60, string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Live.getCounters', [
            'lastMinutes' => $lastMinutes,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get information about the last visits
     *
     *
     * @throws InvalidRequestException
     * @internal param int $maxIdVisit
     * @internal param int $filterLimit
     */
    public function getLastVisitsDetails(string $segment = '', string $minTimestamp = '', string $doNotFetchActions = '', array $optional = []): bool|object
    {
        return $this->_request('Live.getLastVisitsDetails', [
            'segment' => $segment,
            'minTimestamp' => $minTimestamp,
            'doNotFetchActions' => $doNotFetchActions,
        ], $optional);
    }

    /**
     * Get a profile for a visitor
     *
     *
     * @throws InvalidRequestException
     */
    public function getVisitorProfile(string $visitorId = '', string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Live.getVisitorProfile', [
            'visitorId' => $visitorId,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get the ID of the most recent visitor
     *
     * @throws InvalidRequestException
     */
    public function getMostRecentVisitorId(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Live.getMostRecentVisitorId', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get userId for visitors
     *
     * @throws InvalidRequestException
     */
    public function getUsersById(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('UserId.getUsers', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * MODULE: MOBILEMESSAGING
     *
     * The MobileMessaging API lets you manage and access all the MobileMessaging plugin features
     * including : - manage SMS API credential - activate phone numbers - check remaining credits -
     * send SMS
     */
    /**
     * Checks if SMSAPI has been configured

     * @throws InvalidRequestException
     */
    public function areSMSAPICredentialProvided(array $optional = []): bool|object
    {
        return $this->_request('MobileMessaging.areSMSAPICredentialProvided', [], $optional);
    }

    /**
     * Get list with sms provider

     * @throws InvalidRequestException
     */
    public function getSMSProvider(array $optional = []): bool|object
    {
        return $this->_request('MobileMessaging.getSMSProvider', [], $optional);
    }

    /**
     * Set SMSAPI credentials
     *
     *
     * @throws InvalidRequestException
     */
    public function setSMSAPICredential(string $provider, string $apiKey, array $optional = []): bool|object
    {
        return $this->_request('MobileMessaging.setSMSAPICredential', [
            'provider' => $provider,
            'apiKey' => $apiKey,
        ], $optional);
    }

    /**
     * Add phone number
     *
     *
     * @throws InvalidRequestException
     */
    public function addPhoneNumber(string $phoneNumber, array $optional = []): bool|object
    {
        return $this->_request('MobileMessaging.addPhoneNumber', [
            'phoneNumber' => $phoneNumber,
        ], $optional);
    }

    /**
     * Get credits left
     *
     * @throws InvalidRequestException
     */
    public function getCreditLeft(array $optional = []): bool|object
    {
        return $this->_request('MobileMessaging.getCreditLeft', [], $optional);
    }

    /**
     * Remove phone number
     *
     *
     * @throws InvalidRequestException
     */
    public function removePhoneNumber(string $phoneNumber, array $optional = []): bool|object
    {
        return $this->_request('MobileMessaging.removePhoneNumber', [
            'phoneNumber' => $phoneNumber,
        ], $optional);
    }

    /**
     * Validate phone number
     *
     *
     * @throws InvalidRequestException
     */
    public function validatePhoneNumber(string $phoneNumber, string $verificationCode, array $optional = []): bool|object
    {
        return $this->_request('MobileMessaging.validatePhoneNumber', [
            'phoneNumber' => $phoneNumber,
            'verificationCode' => $verificationCode,
        ], $optional);
    }

    /**
     * Delete SMSAPI credentials

     * @throws InvalidRequestException
     */
    public function deleteSMSAPICredential(array $optional = []): bool|object
    {
        return $this->_request('MobileMessaging.deleteSMSAPICredential', [], $optional);
    }

    /**
     * Unknown
     *
     * @throws InvalidRequestException
     */
    public function setDelegatedManagement(string $delegatedManagement, array $optional = []): bool|object
    {
        return $this->_request('MobileMessaging.setDelegatedManagement', [
            'delegatedManagement' => $delegatedManagement,
        ], $optional);
    }

    /**
     * Unknown

     * @throws InvalidRequestException
     */
    public function getDelegatedManagement(array $optional = []): bool|object
    {
        return $this->_request('MobileMessaging.getDelegatedManagement', [], $optional);
    }


    /**
     * MODULE: MULTI SITES
     * Get information about multiple sites
     */
    /**
     * Get information about multiple sites
     *
     *
     * @throws InvalidRequestException
     */
    public function getMultiSites(string $segment = '', string $enhanced = '', array $optional = []): bool|object
    {
        return $this->_request('MultiSites.getAll', [
            'segment' => $segment,
            'enhanced' => $enhanced,
        ], $optional);
    }

    /**
     * Get key metrics about one of the sites the user manages
     *
     *
     * @throws InvalidRequestException
     */
    public function getOne(string $segment = '', string $enhanced = '', array $optional = []): bool|object
    {
        return $this->_request('MultiSites.getOne', [
            'segment' => $segment,
            'enhanced' => $enhanced,
        ], $optional);
    }

    /**
     * MODULE: OVERLAY
     */
    /**
     * Unknown

     * @throws InvalidRequestException
     */
    public function getOverlayTranslations(array $optional = []): bool|object
    {
        return $this->_request('Overlay.getTranslations', [], $optional);
    }

    /**
     * Get overlay excluded query parameters

     * @throws InvalidRequestException
     */
    public function getOverlayExcludedQueryParameters(array $optional = []): bool|object
    {
        return $this->_request('Overlay.getExcludedQueryParameters', [], $optional);
    }

    /**
     * Get overlay following pages
     *
     * @throws InvalidRequestException
     */
    public function getOverlayFollowingPages(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Overlay.getFollowingPages', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * MODULE: PROVIDER
     * Get provider information
     */
    /**
     * Get information about visitors internet providers
     *
     * @throws InvalidRequestException
     */
    public function getProvider(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Provider.getProvider', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * MODULE: REFERRERS
     * Get information about the referrers
     */
    /**
     * Get referrer types
     *
     *
     * @throws InvalidRequestException
     */
    public function getReferrerType(string $segment = '', string $typeReferrer = '', array $optional = []): bool|object
    {
        return $this->_request('Referrers.getReferrerType', [
            'segment' => $segment,
            'typeReferrer' => $typeReferrer,
        ], $optional);
    }

    /**
     * Get all referrers
     *
     * @throws InvalidRequestException
     */
    public function getAllReferrers(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Referrers.getAll', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get referrer keywords
     *
     * @throws InvalidRequestException
     */
    public function getKeywords(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Referrers.getKeywords', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get keywords for an url
     *
     *
     * @throws InvalidRequestException
     */
    public function getKeywordsForPageUrl(string $url, array $optional = []): bool|object
    {
        return $this->_request('Referrers.getKeywordsForPageUrl', [
            'url' => $url,
        ], $optional);
    }

    /**
     * Get keywords for a page title.
     *
     *
     * @throws InvalidRequestException
     */
    public function getKeywordsForPageTitle(string $title, array $optional = []): bool|object
    {
        return $this->_request('Referrers.getKeywordsForPageTitle', [
            'title' => $title,
        ], $optional);
    }

    /**
     * Get search engines by keyword
     *
     * @throws InvalidRequestException
     */
    public function getSearchEnginesFromKeywordId(int $idSubtable, string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Referrers.getSearchEnginesFromKeywordId', [
            'idSubtable' => $idSubtable,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get search engines
     *
     * @throws InvalidRequestException
     */
    public function getSearchEngines(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Referrers.getSearchEngines', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get search engines by search engine ID
     *
     * @throws InvalidRequestException
     */
    public function getKeywordsFromSearchEngineId(int $idSubtable, string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Referrers.getKeywordsFromSearchEngineId', [
            'idSubtable' => $idSubtable,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get campaigns
     *
     * @throws InvalidRequestException
     */
    public function getCampaigns(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Referrers.getCampaigns', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get keywords by campaign ID
     *
     * @throws InvalidRequestException
     */
    public function getKeywordsFromCampaignId(int $idSubtable, string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Referrers.getKeywordsFromCampaignId', [
            'idSubtable' => $idSubtable,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get name
     * from advanced campaign reporting
     *
     * @throws InvalidRequestException
     */
    public function getAdvancedCampaignReportingName(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('AdvancedCampaignReporting.getName', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get keyword content from name id
     * from advanced campaign reporting
     *
     * @throws InvalidRequestException
     */
    public function getAdvancedCampaignReportingKeywordContentFromNameId(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('AdvancedCampaignReporting.getKeywordContentFromNameId', [
            'segment' => $segment
        ], $optional);
    }

    /**
     * Get keyword
     * from advanced campaign reporting
     *
     * @throws InvalidRequestException
     */
    public function getAdvancedCampaignReportingKeyword(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('AdvancedCampaignReporting.getKeyword', [
            'segment' => $segment
        ], $optional);
    }

    /**
     * Get source     *
     * from advanced campaign reporting
     *
     * @throws InvalidRequestException
     */
    public function getAdvancedCampaignReportingSource(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('AdvancedCampaignReporting.getSource', [
            'segment' => $segment
        ], $optional);
    }

    /**
     * Get medium
     * from advanced campaign reporting
     *
     * @throws InvalidRequestException
     */
    public function getAdvancedCampaignReportingMedium(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('AdvancedCampaignReporting.getMedium', [
            'segment' => $segment
        ], $optional);
    }

    /**
     * Get content
     * from advanced campaign reporting
     *
     * @throws InvalidRequestException
     */
    public function getAdvancedCampaignReportingContent(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('AdvancedCampaignReporting.getContent', [
            'segment' => $segment
        ], $optional);
    }

    /**
     * Get source and medium
     * from advanced campaign reporting
     *
     * @throws InvalidRequestException
     */
    public function getAdvancedCampaignReportingSourceMedium(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('AdvancedCampaignReporting.getSourceMedium', [
            'segment' => $segment
        ], $optional);
    }

    /**
     * Get name from source and medium by ID
     * from advanced campaign reporting
     *
     * @throws InvalidRequestException
     */
    public function getAdvancedCampaignReportingNameFromSourceMediumId(int $idSubtable, string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('AdvancedCampaignReporting.getNameFromSourceMediumId', [
            'idSubtable' => $idSubtable,
            'segment' => $segment
        ], $optional);
    }

    /**
     * Get website referrals.
     *
     * @throws InvalidRequestException
     */
    public function getWebsites(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Referrers.getWebsites', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get URLs by website ID
     *
     * @throws InvalidRequestException
     */
    public function getUrlsFromWebsiteId(int $idSubtable, string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Referrers.getUrlsFromWebsiteId', [
            'idSubtable' => $idSubtable,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get social referrals
     *
     * @throws InvalidRequestException
     */
    public function getSocials(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Referrers.getSocials', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get social referral urls
     *
     * @throws InvalidRequestException
     */
    public function getUrlsForSocial(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Referrers.getUrlsForSocial', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get the number of distinct search engines
     *
     * @throws InvalidRequestException
     */
    public function getNumberOfSearchEngines(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Referrers.getNumberOfDistinctSearchEngines', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get the number of distinct keywords
     *
     * @throws InvalidRequestException
     */
    public function getNumberOfKeywords(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Referrers.getNumberOfDistinctKeywords', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get the number of distinct campaigns
     *
     * @throws InvalidRequestException
     */
    public function getNumberOfCampaigns(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Referrers.getNumberOfDistinctCampaigns', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get the number of distinct websites
     *
     * @throws InvalidRequestException
     */
    public function getNumberOfWebsites(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Referrers.getNumberOfDistinctWebsites', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get the number of distinct websites urls
     *
     * @throws InvalidRequestException
     */
    public function getNumberOfWebsitesUrls(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Referrers.getNumberOfDistinctWebsitesUrls', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * MODULE: SEO
     * Get SEO information
     */
    /**
     * Get the SEO rank of an url
     *
     *
     * @throws InvalidRequestException
     */
    public function getSeoRank(string $url, array $optional = []): bool|object
    {
        return $this->_request('SEO.getRank', [
            'url' => $url,
        ], $optional);
    }

    /**
     * MODULE: SCHEDULED REPORTS
     * Manage pdf reports
     */
    /**
     * Add scheduled report
     *
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
    ): bool|object
    {
        return $this->_request('ScheduledReports.addReport', [
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
     * Updated scheduled report
     *
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
    ): bool|object
    {
        return $this->_request('ScheduledReports.updateReport', [
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
     * Delete scheduled report
     *
     *
     * @throws InvalidRequestException
     */
    public function deleteReport(int $idReport, array $optional = []): bool|object
    {
        return $this->_request('ScheduledReports.deleteReport', [
            'idReport' => $idReport,
        ], $optional);
    }

    /**
     * Get list of scheduled reports
     *
     *
     * @throws InvalidRequestException
     */
    public function getReports(
        string $idReport = '',
        string $ifSuperUserReturnOnlySuperUserReports = '',
        string $idSegment = '',
        array $optional = []
    ): bool|object
    {
        return $this->_request('ScheduledReports.getReports', [
            'idReport' => $idReport,
            'ifSuperUserReturnOnlySuperUserReports' => $ifSuperUserReturnOnlySuperUserReports,
            'idSegment' => $idSegment,
        ], $optional);
    }

    /**
     * Get list of scheduled reports
     *
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
    ): bool|object
    {
        return $this->_request('ScheduledReports.generateReport', [
            'idReport' => $idReport,
            'language' => $language,
            'outputType' => $outputType,
            'reportFormat' => $reportFormat,
            'parameters' => $parameters,
        ], $optional);
    }

    /**
     * Send scheduled reports
     *
     *
     * @throws InvalidRequestException
     */
    public function sendReport(int $idReport, string $force = '', array $optional = []): bool|object
    {
        return $this->_request('ScheduledReports.sendReport', [
            'idReport' => $idReport,
            'force' => $force,
        ], $optional);
    }

    /**
     * MODULE: SEGMENT EDITOR
     */
    /**
     * Check if current user can add new segments

     * @throws InvalidRequestException
     */
    public function isUserCanAddNewSegment(array $optional = []): bool|object
    {
        return $this->_request('SegmentEditor.isUserCanAddNewSegment', [], $optional);
    }

    /**
     * Delete a segment
     *
     *
     * @throws InvalidRequestException
     */
    public function deleteSegment(int $idSegment, array $optional = []): bool|object
    {
        return $this->_request('SegmentEditor.delete', [
            'idSegment' => $idSegment,
        ], $optional);
    }

    /**
     * Updates a segment
     *
     *
     * @throws InvalidRequestException
     */
    public function updateSegment(
        int $idSegment,
        string $name,
        string $definition,
        string $autoArchive = '',
        string $enableAllUsers = '',
        array $optional = []
    ): bool|object
    {
        return $this->_request('SegmentEditor.update', [
            'idSegment' => $idSegment,
            'name' => $name,
            'definition' => $definition,
            'autoArchive' => $autoArchive,
            'enableAllUsers' => $enableAllUsers,
        ], $optional);
    }

    /**
     * Updates a segment
     *
     *
     * @throws InvalidRequestException
     */
    public function addSegment(string $name, string $definition, string $autoArchive = '', string $enableAllUsers = '', array $optional = []): bool|object
    {
        return $this->_request('SegmentEditor.add', [
            'name' => $name,
            'definition' => $definition,
            'autoArchive' => $autoArchive,
            'enableAllUsers' => $enableAllUsers,
        ], $optional);
    }

    /**
     * Get a segment
     *
     *
     * @throws InvalidRequestException
     */
    public function getSegment(int $idSegment, array $optional = []): bool|object
    {
        return $this->_request('SegmentEditor.get', [
            'idSegment' => $idSegment,
        ], $optional);
    }

    /**
     * Get all segments

     * @throws InvalidRequestException
     */
    public function getAllSegments(array $optional = []): bool|object
    {
        return $this->_request('SegmentEditor.getAll', [], $optional);
    }

    /**
     * MODULE: SITES MANAGER
     * Manage sites
     */
    /**
     * Get the JS tag of the current site
     *
     *
     * @throws InvalidRequestException
     */
    public function getJavascriptTag(
        string $matomoUrl,
        string $mergeSubdomains = '',
        string $groupPageTitlesByDomain = '',
        string $mergeAliasUrls = '',
        string $visitorCustomVariables = '',
        string $pageCustomVariables = '',
        string $customCampaignNameQueryParam = '',
        string $customCampaignKeywordParam = '',
        string $doNotTrack = '',
        string $disableCookies = '',
        array $optional = []
    ): bool|object
    {
        return $this->_request('SitesManager.getJavascriptTag', [
            'piwikUrl' => $matomoUrl,
            'mergeSubdomains' => $mergeSubdomains,
            'groupPageTitlesByDomain' => $groupPageTitlesByDomain,
            'mergeAliasUrls' => $mergeAliasUrls,
            'visitorCustomVariables' => $visitorCustomVariables,
            'pageCustomVariables' => $pageCustomVariables,
            'customCampaignNameQueryParam' => $customCampaignNameQueryParam,
            'customCampaignKeywordParam' => $customCampaignKeywordParam,
            'doNotTrack' => $doNotTrack,
            'disableCookies' => $disableCookies,
        ], $optional);
    }

    /**
     * Get image tracking code of the current site
     *
     *
     * @throws InvalidRequestException
     */
    public function getImageTrackingCode(
        string $matomoUrl,
        string $actionName = '',
        string $idGoal = '',
        string $revenue = '',
        array $optional = []
    ): bool|object
    {
        return $this->_request('SitesManager.getImageTrackingCode', [
            'piwikUrl' => $matomoUrl,
            'actionName' => $actionName,
            'idGoal' => $idGoal,
            'revenue' => $revenue,
        ], $optional);
    }

    /**
     * Get sites from a group
     *
     *
     * @throws InvalidRequestException
     */
    public function getSitesFromGroup(string $group, array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getSitesFromGroup', [
            'group' => $group,
        ], $optional);
    }

    /**
     * Get all site groups.
     * Requires superuser access.

     * @throws InvalidRequestException
     */
    public function getSitesGroups(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getSitesGroups', [], $optional);
    }

    /**
     * Get information about the current site

     * @throws InvalidRequestException
     */
    public function getSiteInformation(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getSiteFromId', [], $optional);
    }

    /**
     * Get urls from current site

     * @throws InvalidRequestException
     */
    public function getSiteUrls(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getSiteUrlsFromId', [], $optional);
    }

    /**
     * Get all sites

     * @throws InvalidRequestException
     */
    public function getAllSites(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getAllSites', [], $optional);
    }

    /**
     * Get all sites with ID

     * @throws InvalidRequestException
     */
    public function getAllSitesId(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getAllSitesId', [], $optional);
    }

    /**
     * Get all sites where the current user has admin access

     * @throws InvalidRequestException
     */
    public function getSitesWithAdminAccess(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getSitesWithAdminAccess', [], $optional);
    }

    /**
     * Get all sites where the current user has view access

     * @throws InvalidRequestException
     */
    public function getSitesWithViewAccess(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getSitesWithViewAccess', [], $optional);
    }

    /**
     * Get all sites where the current user has at least view access.
     *
     *
     * @throws InvalidRequestException
     */
    public function getSitesWithAtLeastViewAccess(string $limit = '', array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getSitesWithAtLeastViewAccess', [
            'limit' => $limit,
        ], $optional);
    }

    /**
     * Get all sites with ID where the current user has admin access

     * @throws InvalidRequestException
     */
    public function getSitesIdWithAdminAccess(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getSitesIdWithAdminAccess', [], $optional);
    }

    /**
     * Get all sites with ID where the current user has view access

     * @throws InvalidRequestException
     */
    public function getSitesIdWithViewAccess(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getSitesIdWithViewAccess', [], $optional);
    }

    /**
     * Get all sites with ID where the current user has at least view access

     * @throws InvalidRequestException
     */
    public function getSitesIdWithAtLeastViewAccess(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getSitesIdWithAtLeastViewAccess', [], $optional);
    }

    /**
     * Get a Matomo site by its URL.
     *
     *
     * @throws InvalidRequestException
     */
    public function getSitesIdFromSiteUrl(string $url, array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getSitesIdFromSiteUrl', [
            'url' => $url,
        ], $optional);
    }

    /**
     * Get a list of all available settings for a specific site.
     *
     * @throws InvalidRequestException
     */
    public function getSiteSettings(): object|bool
    {
        return $this->_request('SitesManager.getSiteSettings');
    }

    /**
     * Add a website.
     * Requires Super User access.
     *
     * The website is defined by a name and an array of URLs.
     *
     * @param  string  $siteName Site name
     * @param  string  $urls Comma separated list of urls
     * @param  string  $ecommerce Is Ecommerce Reporting enabled for this website?
     * @param  string  $searchKeywordParameters Comma separated list of search keyword parameter names
     * @param  string  $searchCategoryParameters Comma separated list of search category parameter names
     * @param  string  $excludeIps Comma separated list of IPs to exclude from the reports (allows wildcards)
     * @param  string  $timezone Timezone string, eg. 'Europe/London'
     * @param  string  $currency Currency, eg. 'EUR'
     * @param  string  $group Website group identifier
     * @param  string  $startDate Date at which the statistics for this website will start. Defaults to today's date in YYYY-MM-DD format
     * @param  string  $keepURLFragments If 1, URL fragments will be kept when tracking. If 2, they will be removed. If 0, the default global behavior will be used.
     * @param  string  $settingValues JSON serialized settings eg {settingName: settingValue, ...}
     * @param  string  $type The website type, defaults to "website" if not set.
     * @param  string  $excludeUnknownUrls Track only URL matching one of website URLs
     *
     * @throws InvalidRequestException
     */
    public function addSite(
        string $siteName,
        string $urls,
        string $ecommerce = '',
        string $siteSearch = '',
        string $searchKeywordParameters = '',
        string $searchCategoryParameters = '',
        string $excludeIps = '',
        string $excludedQueryParameters = '',
        string $timezone = '',
        string $currency = '',
        string $group = '',
        string $startDate = '',
        string $excludedUserAgents = '',
        string $keepURLFragments = '',
        string $settingValues = '',
        string $type = '',
        string $excludeUnknownUrls = '',
        array $optional = []
    ): bool|object
    {
        return $this->_request('SitesManager.addSite', [
            'siteName' => $siteName,
            'urls' => $urls,
            'ecommerce' => $ecommerce,
            'siteSearch' => $siteSearch,
            'searchKeywordParameters' => $searchKeywordParameters,
            'searchCategoryParameters' => $searchCategoryParameters,
            'excludeIps' => $excludeIps,
            'excludedQueryParameters' => $excludedQueryParameters,
            'timezone' => $timezone,
            'currency' => $currency,
            'group' => $group,
            'startDate' => $startDate,
            'excludedUserAgents' => $excludedUserAgents,
            'keepURLFragments' => $keepURLFragments,
            'settingValues' => $settingValues,
            'type' => $type,
            'excludeUnknownUrls' => $excludeUnknownUrls,
        ], $optional);
    }

    /**
     * Delete current site

     * @throws InvalidRequestException
     */
    public function deleteSite(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.deleteSite', [], $optional);
    }

    /**
     * Add alias urls for the current site
     *
     *
     * @throws InvalidRequestException
     */
    public function addSiteAliasUrls(array $urls, array $optional = []): bool|object
    {
        return $this->_request('SitesManager.addSiteAliasUrls', [
            'urls' => $urls,
        ], $optional);
    }

    /**
     * Set alias urls for the current site
     *
     *
     * @throws InvalidRequestException
     */
    public function setSiteAliasUrls(array $urls, array $optional = []): bool|object
    {
        return $this->_request('SitesManager.setSiteAliasUrls', [
            'urls' => $urls,
        ], $optional);
    }

    /**
     * Get IP's for a specific range
     *
     *
     * @throws InvalidRequestException
     */
    public function getIpsForRange(string $ipRange, array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getIpsForRange', [
            'ipRange' => $ipRange,
        ], $optional);
    }

    /**
     * Set the global excluded IP's
     *
     *
     * @throws InvalidRequestException
     */
    public function setExcludedIps(array $excludedIps, array $optional = []): bool|object
    {
        return $this->_request('SitesManager.setGlobalExcludedIps', [
            'excludedIps' => $excludedIps,
        ], $optional);
    }

    /**
     * Set global search parameters
     *
     * @throws InvalidRequestException
     */
    public function setGlobalSearchParameters($searchKeywordParameters, $searchCategoryParameters, array $optional = []): bool|object
    {
        return $this->_request('SitesManager.setGlobalSearchParameters ', [
            'searchKeywordParameters' => $searchKeywordParameters,
            'searchCategoryParameters' => $searchCategoryParameters,
        ], $optional);
    }

    /**
     * Get search keywords

     * @throws InvalidRequestException
     */
    public function getSearchKeywordParametersGlobal(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getSearchKeywordParametersGlobal', [], $optional);
    }

    /**
     * Get search categories

     * @throws InvalidRequestException
     */
    public function getSearchCategoryParametersGlobal(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getSearchCategoryParametersGlobal', [], $optional);
    }

    /**
     * Get the global excluded query parameters

     * @throws InvalidRequestException
     */
    public function getExcludedParameters(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getExcludedQueryParametersGlobal', [], $optional);
    }

    /**
     * Get the global excluded user agents

     * @throws InvalidRequestException
     */
    public function getExcludedUserAgentsGlobal(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getExcludedUserAgentsGlobal', [], $optional);
    }

    /**
     * Set the global excluded user agents
     *
     *
     * @throws InvalidRequestException
     */
    public function setGlobalExcludedUserAgents(array $excludedUserAgents, array $optional = []): bool|object
    {
        return $this->_request('SitesManager.setGlobalExcludedUserAgents', [
            'excludedUserAgents' => $excludedUserAgents,
        ], $optional);
    }

    /**
     * Check if site specific user agent exclude is enabled

     * @throws InvalidRequestException
     */
    public function isSiteSpecificUserAgentExcludeEnabled(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.isSiteSpecificUserAgentExcludeEnabled', [], $optional);
    }

    /**
     * Set site specific user agent exclude
     *
     *
     * @throws InvalidRequestException
     */
    public function setSiteSpecificUserAgentExcludeEnabled(int $enabled, array $optional = []): bool|object
    {
        return $this->_request('SitesManager.setSiteSpecificUserAgentExcludeEnabled', [
            'enabled' => $enabled,
        ], $optional);
    }

    /**
     * Check if the url fragments should be global

     * @throws InvalidRequestException
     */
    public function getKeepURLFragmentsGlobal(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getKeepURLFragmentsGlobal', [], $optional);
    }

    /**
     * Set the url fragments global
     *
     *
     * @throws InvalidRequestException
     */
    public function setKeepURLFragmentsGlobal(int $enabled, array $optional = []): bool|object
    {
        return $this->_request('SitesManager.setKeepURLFragmentsGlobal', [
            'enabled' => $enabled,
        ], $optional);
    }

    /**
     * Set the global excluded query parameters
     *
     *
     * @throws InvalidRequestException
     */
    public function setExcludedParameters(array $excludedQueryParameters, array $optional = []): bool|object
    {
        return $this->_request('SitesManager.setGlobalExcludedQueryParameters', [
            'excludedQueryParameters' => $excludedQueryParameters,
        ], $optional);
    }

    /**
     * Get the global excluded IP's

     * @throws InvalidRequestException
     */
    public function getExcludedIps(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getExcludedIpsGlobal', [], $optional);
    }

    /**
     * Get the default currency

     * @throws InvalidRequestException
     */
    public function getDefaultCurrency(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getDefaultCurrency', [], $optional);
    }

    /**
     * Set the default currency
     *
     *
     * @throws InvalidRequestException
     */
    public function setDefaultCurrency(string $defaultCurrency, array $optional = []): bool|object
    {
        return $this->_request('SitesManager.setDefaultCurrency', [
            'defaultCurrency' => $defaultCurrency,
        ], $optional);
    }

    /**
     * Get the default timezone

     * @throws InvalidRequestException
     */
    public function getDefaultTimezone(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getDefaultTimezone', [], $optional);
    }

    /**
     * Set the default timezone
     *
     *
     * @throws InvalidRequestException
     */
    public function setDefaultTimezone(string $defaultTimezone, array $optional = []): bool|object
    {
        return $this->_request('SitesManager.setDefaultTimezone', [
            'defaultTimezone' => $defaultTimezone,
        ], $optional);
    }

    /**
     * Update current site
     *
     *
     * @throws InvalidRequestException
     */
    public function updateSite(
        string $siteName,
        array $urls,
        bool|string $ecommerce = '',
        bool|string $siteSearch = '',
        string $searchKeywordParameters = '',
        string $searchCategoryParameters = '',
        array|string $excludeIps = '',
        array|string $excludedQueryParameters = '',
        string $timezone = '',
        string $currency = '',
        string $group = '',
        string $startDate = '',
        string $excludedUserAgents = '',
        string $keepURLFragments = '',
        string $type = '',
        string $settings = '',
        array $optional = []
    ): bool|object
    {
        return $this->_request('SitesManager.updateSite', [
            'siteName' => $siteName,
            'urls' => $urls,
            'ecommerce' => $ecommerce,
            'siteSearch' => $siteSearch,
            'searchKeywordParameters' => $searchKeywordParameters,
            'searchCategoryParameters' => $searchCategoryParameters,
            'excludeIps' => $excludeIps,
            'excludedQueryParameters' => $excludedQueryParameters,
            'timezone' => $timezone,
            'currency' => $currency,
            'group' => $group,
            'startDate' => $startDate,
            'excludedUserAgents' => $excludedUserAgents,
            'keepURLFragments' => $keepURLFragments,
            'type' => $type,
            'settings' => $settings,
        ], $optional);
    }

    /**
     * Get a list with all available currencies

     * @throws InvalidRequestException
     */
    public function getCurrencyList(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getCurrencyList', [], $optional);
    }

    /**
     * Get a list with all currency symbols

     * @throws InvalidRequestException
     */
    public function getCurrencySymbols(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getCurrencySymbols', [], $optional);
    }

    /**
     * Get a list with available timezones

     * @throws InvalidRequestException
     */
    public function getTimezonesList(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getTimezonesList', [], $optional);
    }

    /**
     * Unknown

     * @throws InvalidRequestException
     */
    public function getUniqueSiteTimezones(array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getUniqueSiteTimezones', [], $optional);
    }

    /**
     * Rename group
     *
     *
     * @throws InvalidRequestException
     */
    public function renameGroup(string $oldGroupName, string $newGroupName, array $optional = []): bool|object
    {
        return $this->_request('SitesManager.renameGroup', [
            'oldGroupName' => $oldGroupName,
            'newGroupName' => $newGroupName,
        ], $optional);
    }

    /**
     * Get all sites which matches the pattern
     *
     *
     * @throws InvalidRequestException
     */
    public function getPatternMatchSites(string $pattern, array $optional = []): bool|object
    {
        return $this->_request('SitesManager.getPatternMatchSites', [
            'pattern' => $pattern,
        ], $optional);
    }

    /**
     * MODULE: TRANSITIONS
     * Get transitions for page URLs, titles and actions
     */
    /**
     * Get transitions for a page title
     *
     * @throws InvalidRequestException
     */
    public function getTransitionsForPageTitle(string $pageTitle, string $segment = '',
        string $limitBeforeGrouping = '', array $optional = []): bool|object
    {
        return $this->_request('Transitions.getTransitionsForPageTitle', [
            'pageTitle' => $pageTitle,
            'segment' => $segment,
            'limitBeforeGrouping' => $limitBeforeGrouping,
        ], $optional);
    }

    /**
     * Get transitions for a page URL
     *
     * @throws InvalidRequestException
     */
    public function getTransitionsForPageUrl(string $pageUrl, string $segment = '',
        string $limitBeforeGrouping = '', array $optional = []): bool|object
    {
        return $this->_request('Transitions.getTransitionsForPageTitle', [
            'pageUrl' => $pageUrl,
            'segment' => $segment,
            'limitBeforeGrouping' => $limitBeforeGrouping,
        ], $optional);
    }

    /**
     * Get transitions for a page URL
     *
     * @throws \VisualAppeal\InvalidRequestException
     */
    public function getTransitionsForAction(
        string $actionName,
        $actionType,
        string $segment = '',
        string $limitBeforeGrouping = '',
        string $parts = 'all',
        string $returnNormalizedUrls = '',
        array $optional = []
    ): bool|object
    {
        return $this->_request('Transitions.getTransitionsForAction', [
            'actionName' => $actionName,
            'actionType' => $actionType,
            'segment' => $segment,
            'limitBeforeGrouping' => $limitBeforeGrouping,
            'parts' => $parts,
            'returnNormalizedUrls' => $returnNormalizedUrls,
        ], $optional);
    }

    /**
     * Get translations for the transitions

     * @throws InvalidRequestException
     */
    public function getTransitionsTranslations(array $optional = []): bool|object
    {
        return $this->_request('Transitions.getTranslations', [], $optional);
    }

    /**
     * MODULE: USER COUNTRY
     * Get visitors country information
     */
    /**
     * Get countries of all visitors
     *
     *
     * @throws InvalidRequestException
     */
    public function getCountry(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('UserCountry.getCountry', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get a list of used country codes to country names
     *
     * @throws InvalidRequestException
     */
    public function getCountryCodeMapping(): object
    {
        return $this->_request('UserCountry.getCountryCodeMapping');
    }

    /**
     * Get continents of all visitors
     *
     *
     * @throws InvalidRequestException
     */
    public function getContinent(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('UserCountry.getContinent', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get regions of all visitors
     *
     *
     * @throws InvalidRequestException
     */
    public function getRegion(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('UserCountry.getRegion', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get cities of all visitors.
     *
     *
     * @throws InvalidRequestException
     */
    public function getCity(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('UserCountry.getCity', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get location from IP address.
     *
     *
     * @throws InvalidRequestException
     */
    public function getLocationFromIP(string $ip, string $provider = '', array $optional = []): object|bool
    {
        return $this->_request('UserCountry.getLocationFromIP', [
            'ip' => $ip,
            'provider' => $provider,
        ], $optional);
    }

    /**
     * Get the number of distinct countries.
     *
     *
     * @throws InvalidRequestException
     */
    public function getCountryNumber(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('UserCountry.getNumberOfDistinctCountries', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * MODULE: USER RESOLUTION
     *
     * Get screen resolutions
     */
    /**
     * Get resolution
     *
     *
     * @throws InvalidRequestException
     */
    public function getResolution(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('Resolution.getResolution', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get configuration
     *
     *
     * @throws InvalidRequestException
     */
    public function getConfiguration(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('Resolution.getConfiguration', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * MODULE: DEVICE PLUGINS
     *
     * Get device plugins
     */
    /**
     * Get plugins
     *
     *
     * @throws InvalidRequestException
     */
    public function getUserPlugin(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('DevicePlugins.getPlugin', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * MODULE: USER LANGUAGE
     *
     * Get the user language
     */
    /**
     * Get language
     *
     *
     * @throws InvalidRequestException
     */
    public function getUserLanguage(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('UserLanguage.getLanguage', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get language code
     *
     *
     * @throws InvalidRequestException
     */
    public function getUserLanguageCode(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('UserLanguage.getLanguageCode', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * MODULE: USER MANAGER
     * Manage Matomo users
     */
    /**
     * Set user preference
     *
     * @param  string  $userLogin Username
     *
     * @throws InvalidRequestException
     */
    public function setUserPreference(string $userLogin, string $preferenceName, string $preferenceValue, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.setUserPreference', [
            'userLogin' => $userLogin,
            'preferenceName' => $preferenceName,
            'preferenceValue' => $preferenceValue,
        ], $optional);
    }

    /**
     * Get user preference
     *
     * @param  string  $userLogin Username
     *
     * @throws InvalidRequestException
     */
    public function getUserPreference(string $userLogin, string $preferenceName, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getUserPreference', [
            'userLogin' => $userLogin,
            'preferenceName' => $preferenceName,
        ], $optional);
    }

    /**
     * Get user by username
     *
     * @param  string  $userLogins Comma separated list with usernames
     *
     * @throws InvalidRequestException
     */
    public function getUsers(string $userLogins = '', array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getUsers', [
            'userLogins' => $userLogins,
        ], $optional);
    }

    /**
     * Get all user logins
     *
     *
     * @throws InvalidRequestException
     */
    public function getUsersLogin(array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getUsersLogin', [], $optional);
    }

    /**
     * Get sites by user access
     *
     *
     * @throws InvalidRequestException
     */
    public function getUsersSitesFromAccess(string $access, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getUsersSitesFromAccess', [
            'access' => $access,
        ], $optional);
    }

    /**
     * Get all users with access level from the current site
     *
     *
     * @throws InvalidRequestException
     */
    public function getUsersAccess(array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getUsersAccessFromSite', [], $optional);
    }

    /**
     * Get all users with access $access to the current site
     *
     *
     * @throws InvalidRequestException
     */
    public function getUsersWithSiteAccess(string $access, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getUsersWithSiteAccess', [
            'access' => $access,
        ], $optional);
    }

    /**
     * Get site access from the user $userLogin
     *
     * @param  string  $userLogin Username
     *
     * @throws InvalidRequestException
     */
    public function getSitesAccessFromUser(string $userLogin, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getSitesAccessFromUser', [
            'userLogin' => $userLogin,
        ], $optional);
    }

    /**
     * Get user by login
     *
     * @param  string  $userLogin Username
     *
     * @throws InvalidRequestException
     */
    public function getUser(string $userLogin, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getUser', [
            'userLogin' => $userLogin,
        ], $optional);
    }

    /**
     * Get user by email
     *
     *
     * @throws InvalidRequestException
     */
    public function getUserByEmail(string $userEmail, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getUserByEmail', [
            'userEmail' => $userEmail,
        ], $optional);
    }

    /**
     * Add a user
     *
     * @param  string  $userLogin Username
     * @param  string  $password Password in clear text
     *
     * @throws InvalidRequestException
     */
    public function addUser(string $userLogin, string $password, string $email, string $alias = '', array $optional = []): object|bool
    {
        return $this->_request('UsersManager.addUser', [
            'userLogin' => $userLogin,
            'password' => $password,
            'email' => $email,
            'alias' => $alias,
        ], $optional);
    }

    /**
     * Set superuser access.
     *
     * @param  string  $userLogin Username
     *
     * @throws InvalidRequestException
     */
    public function setSuperUserAccess(string $userLogin, int $hasSuperUserAccess, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.setSuperUserAccess', [
            'userLogin' => $userLogin,
            'hasSuperUserAccess' => $hasSuperUserAccess,
        ], $optional);
    }

    /**
     * Check if user has superuser access.
     *
     *
     * @throws InvalidRequestException
     */
    public function hasSuperUserAccess(array $optional = []): object|bool
    {
        return $this->_request('UsersManager.hasSuperUserAccess', [], $optional);
    }

    /**
     * Get a list of users with superuser access
     *
     *
     * @throws InvalidRequestException
     */
    public function getUsersHavingSuperUserAccess(array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getUsersHavingSuperUserAccess', [], $optional);
    }

    /**
     * Update a user
     *
     * @param  string  $userLogin Username
     * @param  string  $password Password in clear text
     *
     * @throws InvalidRequestException
     */
    public function updateUser(string $userLogin, string $password = '', string $email = '', string $alias = '', array $optional = []): object|bool
    {
        return $this->_request('UsersManager.updateUser', [
            'userLogin' => $userLogin,
            'password' => $password,
            'email' => $email,
            'alias' => $alias,
        ], $optional);
    }

    /**
     * Delete a user
     *
     * @param  string  $userLogin Username
     *
     * @throws InvalidRequestException
     */
    public function deleteUser(string $userLogin, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.deleteUser', [
            'userLogin' => $userLogin,
        ], $optional);
    }

    /**
     * Checks if a user exist
     *
     *
     * @throws InvalidRequestException
     */
    public function userExists(string $userLogin, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.userExists', [
            'userLogin' => $userLogin,
        ], $optional);
    }

    /**
     * Checks if a user exist by email
     *
     *
     * @throws InvalidRequestException
     */
    public function userEmailExists(string $userEmail, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.userEmailExists', [
            'userEmail' => $userEmail,
        ], $optional);
    }

    /**
     * Grant access to multiple sites
     *
     * @param  string  $userLogin Username
     *
     * @throws InvalidRequestException
     */
    public function setUserAccess(string $userLogin, string $access, array $idSites, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.setUserAccess', [
            'userLogin' => $userLogin,
            'access' => $access,
            'idSites' => $idSites,
        ], $optional);
    }

    /**
     * Get the token for a user
     *
     * @param  string  $userLogin Username
     * @param  string  $md5Password Password in clear text
     *
     * @throws InvalidRequestException
     */
    public function getTokenAuth(string $userLogin, string $md5Password, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getTokenAuth', [
            'userLogin' => $userLogin,
            'md5Password' => md5($md5Password),
        ], $optional);
    }

    /**
     * MODULE: VISIT FREQUENCY
     *
     * Get visit frequency
     */
    /**
     * Get the visit frequency
     *
     *
     * @throws InvalidRequestException
     */
    public function getVisitFrequency(string $segment = '', string $columns = '', array $optional = []): object|bool
    {
        return $this->_request('VisitFrequency.get', [
            'segment' => $segment,
            'columns' => $columns,
        ], $optional);
    }

    /**
     * MODULE: VISIT TIME
     * Get visit time
     */
    /**
     * Get the visit by local time
     *
     *
     * @throws InvalidRequestException
     */
    public function getVisitLocalTime(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('VisitTime.getVisitInformationPerLocalTime', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get the visit by server time
     *
     * @param  string  $hideFutureHoursWhenToday Hide the future hours when the report is created for today
     *
     * @throws InvalidRequestException
     */
    public function getVisitServerTime(string $segment = '', string $hideFutureHoursWhenToday = '', array $optional = []): object|bool
    {
        return $this->_request('VisitTime.getVisitInformationPerServerTime', [
            'segment' => $segment,
            'hideFutureHoursWhenToday' => $hideFutureHoursWhenToday,
        ], $optional);
    }

    /**
     * Get the visit by server time
     *
     *
     * @throws InvalidRequestException
     */
    public function getByDayOfWeek(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('VisitTime.getByDayOfWeek', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * MODULE: VISITOR INTEREST
     * Get the interests of the visitor
     */
    /**
     * Get the number of visits per visit duration
     *
     *
     * @throws InvalidRequestException
     */
    public function getNumberOfVisitsPerDuration(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('VisitorInterest.getNumberOfVisitsPerVisitDuration', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get the number of visits per visited page
     *
     *
     * @throws InvalidRequestException
     */
    public function getNumberOfVisitsPerPage(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('VisitorInterest.getNumberOfVisitsPerPage', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get the number of days elapsed since the last visit
     *
     *
     * @throws InvalidRequestException
     */
    public function getNumberOfVisitsByDaySinceLast(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('VisitorInterest.getNumberOfVisitsByDaysSinceLast', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get the number of visits by visit count
     *
     *
     * @throws InvalidRequestException
     */
    public function getNumberOfVisitsByCount(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('VisitorInterest.getNumberOfVisitsByVisitCount', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * MODULE: VISITS SUMMARY
     *
     * Get visit summary information.
     */
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
     * Get visits.
     *
     * @throws InvalidRequestException
     */
    public function getVisits(string $segment = '', array $optional = []): object|bool
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
    public function getUniqueVisitors(string $segment = '', array $optional = []): object|bool
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
     * Get max actions.
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
    public function getSumVisitsLengthPretty(string $segment = '', array $optional = []): object|bool
    {
        return $this->_request('VisitsSummary.getSumVisitsLengthPretty', [
            'segment' => $segment,
        ], $optional);
    }
}
