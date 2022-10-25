<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Example Plugin
 *
 * @link https://github.com/matomo-org/matomo/tree/4.x-dev/plugins/ExamplePlugin
 */
trait ExamplePlugin
{
    /**
     * Get a multidimensional array.
     *
     * @throws InvalidRequestException
     */
    public function getExamplePluginAnswerToLife(int $truth = 1, array $optional = []): mixed
    {
        return $this->request('ExamplePlugin.getAnswerToLife', [
            'truth' => $truth,
        ], $optional);
    }

    /**
     * Get a multidimensional array.
     *
     * @throws InvalidRequestException
     */
    public function getExamplePluginReport(string $segment = '', array $optional = []): mixed
    {
        return $this->request('ExamplePlugin.getExampleReport', [
            'segment' => $segment,
        ], $optional);
    }
}
