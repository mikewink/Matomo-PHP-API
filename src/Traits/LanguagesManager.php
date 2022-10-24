<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Languages Manager
 *
 * The LanguagesManager API lets you access existing Matomo translations, and
 * change Users languages preferences.
 */
trait LanguagesManager
{
    /**
     * Test if a language is available.
     *
     * @throws InvalidRequestException
     */
    public function getLanguageAvailable(string $languageCode, array $optional = []): bool|object
    {
        return $this->_request('LanguagesManager.isLanguageAvailable', [
            'languageCode' => $languageCode,
        ], $optional);
    }

    /**
     * Get all available languages.
     *
     * @throws InvalidRequestException
     */
    public function getAvailableLanguages(array $optional = []): bool|object
    {
        return $this->_request('LanguagesManager.getAvailableLanguages', [], $optional);
    }

    /**
     * Get all available languages with information.
     *
     * @throws InvalidRequestException
     */
    public function getAvailableLanguagesInfo(array $optional = []): bool|object
    {
        return $this->_request('LanguagesManager.getAvailableLanguagesInfo', [], $optional);
    }

    /**
     * Get all available languages with their names.
     *
     * @throws InvalidRequestException
     */
    public function getAvailableLanguageNames(array $optional = []): bool|object
    {
        return $this->_request('LanguagesManager.getAvailableLanguageNames', [], $optional);
    }

    /**
     * Get translations for a language.
     *
     * @throws InvalidRequestException
     */
    public function getTranslations(string $languageCode, array $optional = []): bool|object
    {
        return $this->_request('LanguagesManager.getTranslationsForLanguage', [
            'languageCode' => $languageCode,
        ], $optional);
    }

    /**
     * Get the language for the user with the login $login.
     *
     * @throws InvalidRequestException
     */
    public function getLanguageForUser(string $login, array $optional = []): bool|object
    {
        return $this->_request('LanguagesManager.getLanguageForUser', [
            'login' => $login,
        ], $optional);
    }

    /**
     * Set the language for the user with the login $login.
     *
     * @throws InvalidRequestException
     */
    public function setLanguageForUser(string $login, string $languageCode, array $optional = []): bool|object
    {
        return $this->_request('LanguagesManager.setLanguageForUser', [
            'login' => $login,
            'languageCode' => $languageCode,
        ], $optional);
    }
}
