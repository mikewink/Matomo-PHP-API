<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * Mobile Messaging
 *
 * The MobileMessaging API lets you manage and access all the MobileMessaging
 * plugin features including.
 */
trait MobileMessaging
{
    /**
     * Checks if SMSAPI has been configured.
     *
     * @throws InvalidRequestException
     */
    public function areSMSAPICredentialProvided(array $optional = []): bool|object
    {
        return $this->_request('MobileMessaging.areSMSAPICredentialProvided', [], $optional);
    }

    /**
     * Get list with sms provider.
     *
     * @throws InvalidRequestException
     */
    public function getSMSProvider(array $optional = []): bool|object
    {
        return $this->_request('MobileMessaging.getSMSProvider', [], $optional);
    }

    /**
     * Set SMSAPI credentials
     *
     * @throws InvalidRequestException
     */
    public function setSMSAPICredential(string $provider, string $apiKey, array $optional = []): bool|object
    {
        return $this->_request('MobileMessaging.setSMSAPICredential', [
            'provider' => $provider,
            'apiKey' => $apiKey,
        ], $optional);
    }

    /**
     * Add a phone number.
     *
     * @throws InvalidRequestException
     */
    public function addPhoneNumber(string $phoneNumber, array $optional = []): bool|object
    {
        return $this->_request('MobileMessaging.addPhoneNumber', [
            'phoneNumber' => $phoneNumber,
        ], $optional);
    }

    /**
     * Get credits left.
     *
     * @throws InvalidRequestException
     */
    public function getCreditLeft(array $optional = []): bool|object
    {
        return $this->_request('MobileMessaging.getCreditLeft', [], $optional);
    }

    /**
     * Remove a phone number.
     *
     * @throws InvalidRequestException
     */
    public function removePhoneNumber(string $phoneNumber, array $optional = []): bool|object
    {
        return $this->_request('MobileMessaging.removePhoneNumber', [
            'phoneNumber' => $phoneNumber,
        ], $optional);
    }

    /**
     * Validate a phone number.
     *
     * @throws InvalidRequestException
     */
    public function validatePhoneNumber(string $phoneNumber, string $verificationCode, array $optional = []): bool|object
    {
        return $this->_request('MobileMessaging.validatePhoneNumber', [
            'phoneNumber' => $phoneNumber,
            'verificationCode' => $verificationCode,
        ], $optional);
    }

    /**
     * Delete SMSAPI credentials.
     *
     * @throws InvalidRequestException
     */
    public function deleteSMSAPICredential(array $optional = []): bool|object
    {
        return $this->_request('MobileMessaging.deleteSMSAPICredential', [], $optional);
    }

    /**
     * Unknown.
     *
     * @throws InvalidRequestException
     */
    public function setDelegatedManagement(string $delegatedManagement, array $optional = []): bool|object
    {
        return $this->_request('MobileMessaging.setDelegatedManagement', [
            'delegatedManagement' => $delegatedManagement,
        ], $optional);
    }

    /**
     * Unknown.
     *
     * @throws InvalidRequestException
     */
    public function getDelegatedManagement(array $optional = []): bool|object
    {
        return $this->_request('MobileMessaging.getDelegatedManagement', [], $optional);
    }
}
