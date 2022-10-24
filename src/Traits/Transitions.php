<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Transitions
 *
 * Get transitions for page URLs, titles and actions.
 */
trait Transitions
{
    /**
     * Get transitions for a page title.
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
     * Get transitions for a page URL.
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
     * Get transitions for a page URL.
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
     * Get translations for the transitions.
     *
     * @throws InvalidRequestException
     */
    public function getTransitionsTranslations(array $optional = []): bool|object
    {
        return $this->_request('Transitions.getTranslations', [], $optional);
    }

}
