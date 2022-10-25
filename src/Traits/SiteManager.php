<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Site Manager
 *
 * The SitesManager API gives you full control on Websites in Matomo (create,
 * update and delete), and many methods to retrieve websites based on various
 * attributes.
 */
trait SiteManager
{
    /**
     * Get the JS tag of the current site.
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
    ): mixed
    {
        return $this->request('SitesManager.getJavascriptTag', [
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
     * Get image tracking code of the current site.
     *
     * @throws InvalidRequestException
     */
    public function getImageTrackingCode(
        string $matomoUrl,
        string $actionName = '',
        string $idGoal = '',
        string $revenue = '',
        array $optional = []
    ): mixed
    {
        return $this->request('SitesManager.getImageTrackingCode', [
            'piwikUrl' => $matomoUrl,
            'actionName' => $actionName,
            'idGoal' => $idGoal,
            'revenue' => $revenue,
        ], $optional);
    }

    /**
     * Get sites from a group.
     *
     * @throws InvalidRequestException
     */
    public function getSitesFromGroup(string $group, array $optional = []): mixed
    {
        return $this->request('SitesManager.getSitesFromGroup', [
            'group' => $group,
        ], $optional);
    }

    /**
     * Get all site groups.
     *
     * Requires superuser access.
     *
     * @throws InvalidRequestException
     */
    public function getSitesGroups(array $optional = []): mixed
    {
        return $this->request('SitesManager.getSitesGroups', [], $optional);
    }

    /**
     * Get information about the current site.
     *
     * @throws InvalidRequestException
     */
    public function getSiteInformation(array $optional = []): mixed
    {
        return $this->request(
            method: 'SitesManager.getSiteFromId',
            optional: $optional
        );
    }

    /**
     * Get URLs from current site.
     *
     * @throws InvalidRequestException
     */
    public function getSiteUrls(array $optional = []): mixed
    {
        return $this->request('SitesManager.getSiteUrlsFromId', [], $optional);
    }

    /**
     * Get all sites.
     *
     * @throws InvalidRequestException
     */
    public function getAllSites(array $optional = []): mixed
    {
        return $this->request('SitesManager.getAllSites', [], $optional);
    }

    /**
     * Get all sites with ID.
     *
     * @throws InvalidRequestException
     */
    public function getAllSitesId(array $optional = []): mixed
    {
        return $this->request('SitesManager.getAllSitesId', [], $optional);
    }

    /**
     * Get all sites where the current user has admin access.
     *
     * @throws InvalidRequestException
     */
    public function getSitesWithAdminAccess(array $optional = []): mixed
    {
        return $this->request('SitesManager.getSitesWithAdminAccess', [], $optional);
    }

    /**
     * Get all sites where the current user has view access.
     *
     * @throws InvalidRequestException
     */
    public function getSitesWithViewAccess(array $optional = []): mixed
    {
        return $this->request('SitesManager.getSitesWithViewAccess', [], $optional);
    }

    /**
     * Get all sites where the current user has at least view access.
     *
     * @throws InvalidRequestException
     */
    public function getSitesWithAtLeastViewAccess(string $limit = '', array $optional = []): mixed
    {
        return $this->request('SitesManager.getSitesWithAtLeastViewAccess', [
            'limit' => $limit,
        ], $optional);
    }

    /**
     * Get all sites with ID where the current user has admin access.
     *
     * @throws InvalidRequestException
     */
    public function getSitesIdWithAdminAccess(array $optional = []): mixed
    {
        return $this->request('SitesManager.getSitesIdWithAdminAccess', [], $optional);
    }

    /**
     * Get all sites with ID where the current user has view access.
     *
     * @throws InvalidRequestException
     */
    public function getSitesIdWithViewAccess(array $optional = []): mixed
    {
        return $this->request('SitesManager.getSitesIdWithViewAccess', [], $optional);
    }

    /**
     * Get all sites with ID where the current user has at least view access.
     *
     * @throws InvalidRequestException
     */
    public function getSitesIdWithAtLeastViewAccess(array $optional = []): mixed
    {
        return $this->request('SitesManager.getSitesIdWithAtLeastViewAccess', [], $optional);
    }

    /**
     * Get a Matomo site by its URL.
     *
     * @throws InvalidRequestException
     */
    public function getSitesIdFromSiteUrl(string $url, array $optional = []): mixed
    {
        return $this->request('SitesManager.getSitesIdFromSiteUrl', [
            'url' => $url,
        ], $optional);
    }

    /**
     * Get a list of all available settings for a specific site.
     *
     * @throws InvalidRequestException
     */
    public function getSiteSettings(): mixed
    {
        return $this->request('SitesManager.getSiteSettings');
    }

    /**
     * Add a website.
     *
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
    ): mixed
    {
        return $this->request('SitesManager.addSite', [
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
     * Delete current site.
     *
     * @throws InvalidRequestException
     */
    public function deleteSite(array $optional = []): mixed
    {
        return $this->request('SitesManager.deleteSite', [], $optional);
    }

    /**
     * Add alias urls for the current site.
     *
     * @throws InvalidRequestException
     */
    public function addSiteAliasUrls(array $urls, array $optional = []): mixed
    {
        return $this->request('SitesManager.addSiteAliasUrls', [
            'urls' => $urls,
        ], $optional);
    }

    /**
     * Set alias urls for the current site.
     *
     * @throws InvalidRequestException
     */
    public function setSiteAliasUrls(array $urls, array $optional = []): mixed
    {
        return $this->request('SitesManager.setSiteAliasUrls', [
            'urls' => $urls,
        ], $optional);
    }

    /**
     * Get IP's for a specific range.
     *
     * @throws InvalidRequestException
     */
    public function getIpsForRange(string $ipRange, array $optional = []): mixed
    {
        return $this->request('SitesManager.getIpsForRange', [
            'ipRange' => $ipRange,
        ], $optional);
    }

    /**
     * Set the global excluded IP's
     *
     *
     * @throws InvalidRequestException
     */
    public function setExcludedIps(array $excludedIps, array $optional = []): mixed
    {
        return $this->request('SitesManager.setGlobalExcludedIps', [
            'excludedIps' => $excludedIps,
        ], $optional);
    }

    /**
     * Set global search parameters.
     *
     * @throws InvalidRequestException
     */
    public function setGlobalSearchParameters($searchKeywordParameters, $searchCategoryParameters, array $optional = []): mixed
    {
        return $this->request('SitesManager.setGlobalSearchParameters ', [
            'searchKeywordParameters' => $searchKeywordParameters,
            'searchCategoryParameters' => $searchCategoryParameters,
        ], $optional);
    }

    /**
     * Get search keywords.
     *
     * @throws InvalidRequestException
     */
    public function getSearchKeywordParametersGlobal(array $optional = []): mixed
    {
        return $this->request('SitesManager.getSearchKeywordParametersGlobal', [], $optional);
    }

    /**
     * Get search categories.
     *
     * @throws InvalidRequestException
     */
    public function getSearchCategoryParametersGlobal(array $optional = []): mixed
    {
        return $this->request('SitesManager.getSearchCategoryParametersGlobal', [], $optional);
    }

    /**
     * Get the global excluded query parameters.
     *
     * @throws InvalidRequestException
     */
    public function getExcludedParameters(array $optional = []): mixed
    {
        return $this->request('SitesManager.getExcludedQueryParametersGlobal', [], $optional);
    }

    /**
     * Get the global excluded user agents.
     *
     * @throws InvalidRequestException
     */
    public function getExcludedUserAgentsGlobal(array $optional = []): mixed
    {
        return $this->request('SitesManager.getExcludedUserAgentsGlobal', [], $optional);
    }

    /**
     * Set the global excluded user agents.
     *
     * @throws InvalidRequestException
     */
    public function setGlobalExcludedUserAgents(array $excludedUserAgents, array $optional = []): mixed
    {
        return $this->request('SitesManager.setGlobalExcludedUserAgents', [
            'excludedUserAgents' => $excludedUserAgents,
        ], $optional);
    }

    /**
     * Check if site specific user agent exclude is enabled.
     *
     * @throws InvalidRequestException
     */
    public function isSiteSpecificUserAgentExcludeEnabled(array $optional = []): mixed
    {
        return $this->request('SitesManager.isSiteSpecificUserAgentExcludeEnabled', [], $optional);
    }

    /**
     * Set site specific user agent exclude.
     *
     * @throws InvalidRequestException
     */
    public function setSiteSpecificUserAgentExcludeEnabled(int $enabled, array $optional = []): mixed
    {
        return $this->request('SitesManager.setSiteSpecificUserAgentExcludeEnabled', [
            'enabled' => $enabled,
        ], $optional);
    }

    /**
     * Check if the url fragments should be global.
     *
     * @throws InvalidRequestException
     */
    public function getKeepURLFragmentsGlobal(array $optional = []): mixed
    {
        return $this->request('SitesManager.getKeepURLFragmentsGlobal', [], $optional);
    }

    /**
     * Set the url fragments global.
     *
     * @throws InvalidRequestException
     */
    public function setKeepURLFragmentsGlobal(int $enabled, array $optional = []): mixed
    {
        return $this->request('SitesManager.setKeepURLFragmentsGlobal', [
            'enabled' => $enabled,
        ], $optional);
    }

    /**
     * Set the global excluded query parameters.
     *
     * @throws InvalidRequestException
     */
    public function setExcludedParameters(array $excludedQueryParameters, array $optional = []): mixed
    {
        return $this->request('SitesManager.setGlobalExcludedQueryParameters', [
            'excludedQueryParameters' => $excludedQueryParameters,
        ], $optional);
    }

    /**
     * Get the global excluded IP's.
     *
     * @throws InvalidRequestException
     */
    public function getExcludedIps(array $optional = []): mixed
    {
        return $this->request('SitesManager.getExcludedIpsGlobal', [], $optional);
    }

    /**
     * Get the default currency.
     *
     * @throws InvalidRequestException
     */
    public function getDefaultCurrency(array $optional = []): mixed
    {
        return $this->request('SitesManager.getDefaultCurrency', [], $optional);
    }

    /**
     * Set the default currency.
     *
     * @throws InvalidRequestException
     */
    public function setDefaultCurrency(string $defaultCurrency, array $optional = []): mixed
    {
        return $this->request('SitesManager.setDefaultCurrency', [
            'defaultCurrency' => $defaultCurrency,
        ], $optional);
    }

    /**
     * Get the default timezone.
     *
     * @throws InvalidRequestException
     */
    public function getDefaultTimezone(array $optional = []): mixed
    {
        return $this->request('SitesManager.getDefaultTimezone', [], $optional);
    }

    /**
     * Set the default timezone.
     *
     * @throws InvalidRequestException
     */
    public function setDefaultTimezone(string $defaultTimezone, array $optional = []): mixed
    {
        return $this->request('SitesManager.setDefaultTimezone', [
            'defaultTimezone' => $defaultTimezone,
        ], $optional);
    }

    /**
     * Update current site.
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
    ): mixed
    {
        return $this->request('SitesManager.updateSite', [
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
     * Get a list with all available currencies.
     *
     * @throws InvalidRequestException
     */
    public function getCurrencyList(array $optional = []): mixed
    {
        return $this->request('SitesManager.getCurrencyList', [], $optional);
    }

    /**
     * Get a list with all currency symbols.
     *
     * @throws InvalidRequestException
     */
    public function getCurrencySymbols(array $optional = []): mixed
    {
        return $this->request('SitesManager.getCurrencySymbols', [], $optional);
    }

    /**
     * Get a list with available timezones.
     *
     * @throws InvalidRequestException
     */
    public function getTimezonesList(array $optional = []): mixed
    {
        return $this->request('SitesManager.getTimezonesList', [], $optional);
    }

    /**
     * Unknown
     *
     * @throws InvalidRequestException
     */
    public function getUniqueSiteTimezones(array $optional = []): mixed
    {
        return $this->request('SitesManager.getUniqueSiteTimezones', [], $optional);
    }

    /**
     * Rename a group.
     *
     * @throws InvalidRequestException
     */
    public function renameGroup(string $oldGroupName, string $newGroupName, array $optional = []): mixed
    {
        return $this->request('SitesManager.renameGroup', [
            'oldGroupName' => $oldGroupName,
            'newGroupName' => $newGroupName,
        ], $optional);
    }

    /**
     * Get all sites which matches the pattern.
     *
     * @throws InvalidRequestException
     */
    public function getPatternMatchSites(string $pattern, array $optional = []): mixed
    {
        return $this->request('SitesManager.getPatternMatchSites', [
            'pattern' => $pattern,
        ], $optional);
    }

}
