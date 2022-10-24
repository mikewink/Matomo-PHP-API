<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Events
 *
 * The Events API lets you request reports about your users' Custom Events.
 */
trait Events
{
    /**
     * Get event categories.
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
     * Get event actions.
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
     * Get event names.
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
     * Get action from category ID.
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
     * Get name from category ID.
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
     * Get category from action ID.
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
     * Get name from action ID.
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
     * Get action from name ID.
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
     * Get category from name ID.
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
}
