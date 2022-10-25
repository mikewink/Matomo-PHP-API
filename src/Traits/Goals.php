<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Goals
 *
 * The Goals API lets you create and manage goals for one or several websites.
 */
trait Goals
{
    /**
     * Get all goals.
     *
     * @throws InvalidRequestException
     */
    public function getGoals(array $optional = []): mixed
    {
        return $this->request('Goals.getGoals', [], $optional);
    }

    /**
     * Add a goal.
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
    ): mixed
    {
        return $this->request('Goals.addGoal', [
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
     * Update a goal.
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
    ): mixed
    {
        return $this->request('Goals.updateGoal', [
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
     * Delete a goal.
     *
     * @throws InvalidRequestException
     */
    public function deleteGoal(int $idGoal, array $optional = []): mixed
    {
        return $this->request('Goals.deleteGoal', [
            'idGoal' => $idGoal,
        ], $optional);
    }

    /**
     * Get the SKU of the items.
     *
     * @throws InvalidRequestException
     */
    public function getItemsSku(string $abandonedCarts, array $optional = []): mixed
    {
        return $this->request('Goals.getItemsSku', [
            'abandonedCarts' => $abandonedCarts,
        ], $optional);
    }

    /**
     * Get the name of the items.
     *
     * @throws InvalidRequestException
     */
    public function getItemsName(bool $abandonedCarts, array $optional = []): mixed
    {
        return $this->request('Goals.getItemsName', [
            'abandonedCarts' => $abandonedCarts,
        ], $optional);
    }

    /**
     * Get the categories of the items.
     *
     * @throws InvalidRequestException
     */
    public function getItemsCategory(bool $abandonedCarts, array $optional = []): mixed
    {
        return $this->request('Goals.getItemsCategory', [
            'abandonedCarts' => $abandonedCarts,
        ], $optional);
    }

    /**
     * Get conversion rates from a goal.
     *
     * @throws InvalidRequestException
     */
    public function getGoal(string $segment = '', string $idGoal = '', array $columns = [], array $optional = []): mixed
    {
        return $this->request('Goals.get', [
            'segment' => $segment,
            'idGoal' => $idGoal,
            'columns' => $columns,
        ], $optional);
    }

    /**
     * Get information about a time period, and it's conversion rates.
     *
     * @throws InvalidRequestException
     */
    public function getDaysToConversion(string $segment = '', string $idGoal = '', array $optional = []): mixed
    {
        return $this->request('Goals.getDaysToConversion', [
            'segment' => $segment,
            'idGoal' => $idGoal,
        ], $optional);
    }

    /**
     * Get information about how many site visits create a conversion.
     *
     * @throws InvalidRequestException
     */
    public function getVisitsUntilConversion(string $segment = '', string $idGoal = '', array $optional = []): mixed
    {
        return $this->request('Goals.getVisitsUntilConversion', [
            'segment' => $segment,
            'idGoal' => $idGoal,
        ], $optional);
    }
}
