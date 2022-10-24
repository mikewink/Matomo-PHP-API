<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Referrers
 *
 * The Referrers API lets you access reports about Websites, Search engines,
 * Keywords, Campaigns used to access your website.
 */
trait Referrers
{
    /**
     * Get referrer types.
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
     * Get all referrers.
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
     * Get referrer keywords.
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
     * Get keywords for a URL.
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
     * @throws InvalidRequestException
     */
    public function getKeywordsForPageTitle(string $title, array $optional = []): bool|object
    {
        return $this->_request('Referrers.getKeywordsForPageTitle', [
            'title' => $title,
        ], $optional);
    }

    /**
     * Get search engines by keyword.
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
     * Get search engines.
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
     * Get search engines by search engine ID.
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
     * Get campaigns.
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
     * Get keywords by campaign ID.
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
     * Get name from advanced campaign reporting.
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
     * Get keyword content from name id from advanced campaign reporting.
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
     * Get keyword from advanced campaign reporting.
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
     * Get source from advanced campaign reporting.
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
     * Get medium from advanced campaign reporting.
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
     * Get content from advanced campaign reporting.
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
     * Get source and medium from advanced campaign reporting.
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
     * Get name from source and medium by ID from advanced campaign reporting.
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
     * Get URLs by website ID.
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
     * Get social referrals.
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
     * Get social referral URLs.
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
     * Get the number of distinct search engines.
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
     * Get the number of distinct keywords.
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
     * Get the number of distinct campaigns.
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
     * Get the number of distinct websites.
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
     * Get the number of distinct websites URLs.
     *
     * @throws InvalidRequestException
     */
    public function getNumberOfWebsitesUrls(string $segment = '', array $optional = []): bool|object
    {
        return $this->_request('Referrers.getNumberOfDistinctWebsitesUrls', [
            'segment' => $segment,
        ], $optional);
    }
}
