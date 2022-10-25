<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * User Language
 *
 * The UserLanguage API lets you access reports about your Visitors language
 * setting.
 */
trait UserLanguage
{
    /**
     * Get language.
     *
     * @throws InvalidRequestException
     */
    public function getUserLanguage(string $segment = '', array $optional = []): mixed
    {
        return $this->request('UserLanguage.getLanguage', [
            'segment' => $segment,
        ], $optional);
    }

    /**
     * Get language code.
     *
     * @throws InvalidRequestException
     */
    public function getUserLanguageCode(string $segment = '', array $optional = []): mixed
    {
        return $this->request('UserLanguage.getLanguageCode', [
            'segment' => $segment,
        ], $optional);
    }

}
