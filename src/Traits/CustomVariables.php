<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Custom Variables
 *
 * The Custom Variables API lets you access reports for your custom variables
 * names and values.
 */
trait CustomVariables
{
    /**
     * Get custom variables.
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
     * Get information about a custom variable.
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
}
