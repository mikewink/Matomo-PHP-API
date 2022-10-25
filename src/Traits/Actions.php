<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Actions
 *
 * The Actions API lets you request reports for all your Visitor Actions.
 */
trait Actions
{
    /**
     * Get actions
     *
     * @throws InvalidRequestException
     */
    public function getAction(string $segment = '', string $columns = '', array $optional = []): mixed
    {
        return $this->request('Actions.get', [
            'segment' => $segment,
            'columns' => $columns,
        ], $optional);
    }

    /**
     * Get page urls
     *
     * @throws InvalidRequestException
     */
    public function getPageUrls(string $segment = '', array $optional = []): mixed
    {
        return $this->request('Actions.getPageUrls', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get page URLs after a site search
     *
     * @throws InvalidRequestException
     */
    public function getPageUrlsFollowingSiteSearch(string $segment = '', array $optional = []): mixed
    {
        return $this->request('Actions.getPageUrlsFollowingSiteSearch', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get page titles after a site search
     *
     * @throws InvalidRequestException
     */
    public function getPageTitlesFollowingSiteSearch(string $segment = '', array $optional = []): mixed
    {
        return $this->request('Actions.getPageTitlesFollowingSiteSearch', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get entry page urls
     *
     * @throws InvalidRequestException
     */
    public function getEntryPageUrls(string $segment = '', array $optional = []): mixed
    {
        return $this->request('Actions.getEntryPageUrls', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get exit page urls
     *
     * @throws InvalidRequestException
     */
    public function getExitPageUrls(string $segment = '', array $optional = []): mixed
    {
        return $this->request('Actions.getExitPageUrls', [
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
    public function getPageUrl(string $pageUrl, string $segment = '', array $optional = []): mixed
    {
        return $this->request('Actions.getPageUrl', [
            'pageUrl' => $pageUrl,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get page titles
     *
     * @throws InvalidRequestException
     */
    public function getPageTitles(string $segment = '', array $optional = []): mixed
    {
        return $this->request('Actions.getPageTitles', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get entry page urls
     *
     * @throws InvalidRequestException
     */
    public function getEntryPageTitles(string $segment = '', array $optional = []): mixed
    {
        return $this->request('Actions.getEntryPageTitles', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get exit page urls
     *
     * @throws InvalidRequestException
     */
    public function getExitPageTitles(string $segment = '', array $optional = []): mixed
    {
        return $this->request('Actions.getExitPageTitles', [
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
    public function getPageTitle(string $pageName, string $segment = '', array $optional = []): mixed
    {
        return $this->request('Actions.getPageTitle', [
            'pageName' => $pageName,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get downloads
     *
     * @throws InvalidRequestException
     */
    public function getDownloads(string $segment = '', array $optional = []): mixed
    {
        return $this->request('Actions.getDownloads', [
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
    public function getDownload(string $downloadUrl, string $segment = '', array $optional = []): mixed
    {
        return $this->request('Actions.getDownload', [
            'downloadUrl' => $downloadUrl,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get outlinks
     *
     * @throws InvalidRequestException
     */
    public function getOutlinks(string $segment = '', array $optional = []): mixed
    {
        return $this->request('Actions.getOutlinks', [
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
    public function getOutlink(string $outlinkUrl, string $segment = '', array $optional = []): mixed
    {
        return $this->request('Actions.getOutlink', [
            'outlinkUrl' => $outlinkUrl,
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get the site search keywords
     *
     * @throws InvalidRequestException
     */
    public function getSiteSearchKeywords(string $segment = '', array $optional = []): mixed
    {
        return $this->request('Actions.getSiteSearchKeywords', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get search keywords with no search results
     *
     * @throws InvalidRequestException
     */
    public function getSiteSearchNoResultKeywords(string $segment = '', array $optional = []): mixed
    {
        return $this->request('Actions.getSiteSearchNoResultKeywords', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get site search categories
     *
     * @throws InvalidRequestException
     */
    public function getSiteSearchCategories(string $segment = '', array $optional = []): mixed
    {
        return $this->request('Actions.getSiteSearchCategories', [
            'segment' => $segment,
        ], $optional);
    }

}
