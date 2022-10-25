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
    public function getCustomVariables(string $segment = '', array $optional = []): mixed|array
    {
        return $this->request('CustomVariables.getCustomVariables', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get information about a custom variable.
     *
     * @throws InvalidRequestException
     */
    public function getCustomVariable(int $idSubtable, string $segment = '', array $optional = []): mixed
    {
        return $this->request('CustomVariables.getCustomVariablesValuesFromNameId', [
            'idSubtable' => $idSubtable,
            'segment' => $segment,
        ], $optional);
    }
}
