<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Device Plugins
 *
 * The DevicePlugins API lets you access reports about device plugins such as
 * browser plugins.
 */
trait DevicePlugins
{

    /**
     * Get all plugins.
     *
     * @throws InvalidRequestException
     */
    public function getPlugin(string $segment = '', array $optional = []): mixed
    {
        return $this->request('DevicePlugins.getPlugin', [
            'segment' => $segment,
        ], $optional);
    }
}
