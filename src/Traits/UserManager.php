<?php

declare(strict_types=1);

namespace VisualAppeal\Traits;

use VisualAppeal\InvalidRequestException;

/**
 * User Manager
 *
 * The UsersManager API lets you Manage Users and their permissions to access
 * specific websites.
 */
trait UserManager
{
    /**
     * Set a user preference.
     *
     * @param  string  $userLogin Username
     *
     * @throws InvalidRequestException
     */
    public function setUserPreference(string $userLogin, string $preferenceName, string $preferenceValue, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.setUserPreference', [
            'userLogin' => $userLogin,
            'preferenceName' => $preferenceName,
            'preferenceValue' => $preferenceValue,
        ], $optional);
    }

    /**
     * Get a user preference.
     *
     * @param  string  $userLogin Username
     *
     * @throws InvalidRequestException
     */
    public function getUserPreference(string $userLogin, string $preferenceName, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getUserPreference', [
            'userLogin' => $userLogin,
            'preferenceName' => $preferenceName,
        ], $optional);
    }

    /**
     * Get a user by username.
     *
     * @param  string  $userLogins Comma separated list with usernames
     *
     * @throws InvalidRequestException
     */
    public function getUsers(string $userLogins = '', array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getUsers', [
            'userLogins' => $userLogins,
        ], $optional);
    }

    /**
     * Get all user logins.
     *
     * @throws InvalidRequestException
     */
    public function getUsersLogin(array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getUsersLogin', [], $optional);
    }

    /**
     * Get sites by user access.
     *
     * @throws InvalidRequestException
     */
    public function getUsersSitesFromAccess(string $access, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getUsersSitesFromAccess', [
            'access' => $access,
        ], $optional);
    }

    /**
     * Get all users with access level from the current site.
     *
     * @throws InvalidRequestException
     */
    public function getUsersAccess(array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getUsersAccessFromSite', [], $optional);
    }

    /**
     * Get all users with access $access to the current site.
     *
     * @throws InvalidRequestException
     */
    public function getUsersWithSiteAccess(string $access, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getUsersWithSiteAccess', [
            'access' => $access,
        ], $optional);
    }

    /**
     * Get site access from the user $userLogin.
     *
     * @param  string  $userLogin Username
     *
     * @throws InvalidRequestException
     */
    public function getSitesAccessFromUser(string $userLogin, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getSitesAccessFromUser', [
            'userLogin' => $userLogin,
        ], $optional);
    }

    /**
     * Get a user by login.
     *
     * @param  string  $userLogin Username
     *
     * @throws InvalidRequestException
     */
    public function getUser(string $userLogin, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getUser', [
            'userLogin' => $userLogin,
        ], $optional);
    }

    /**
     * Get user by email.
     *
     * @throws InvalidRequestException
     */
    public function getUserByEmail(string $userEmail, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getUserByEmail', [
            'userEmail' => $userEmail,
        ], $optional);
    }

    /**
     * Add a user.
     *
     * @param  string  $userLogin Username
     * @param  string  $password Password in clear text
     *
     * @throws InvalidRequestException
     */
    public function addUser(string $userLogin, string $password, string $email, string $alias = '', array $optional = []): object|bool
    {
        return $this->_request('UsersManager.addUser', [
            'userLogin' => $userLogin,
            'password' => $password,
            'email' => $email,
            'alias' => $alias,
        ], $optional);
    }

    /**
     * Set superuser access.
     *
     * @param  string  $userLogin Username
     *
     * @throws InvalidRequestException
     */
    public function setSuperUserAccess(string $userLogin, int $hasSuperUserAccess, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.setSuperUserAccess', [
            'userLogin' => $userLogin,
            'hasSuperUserAccess' => $hasSuperUserAccess,
        ], $optional);
    }

    /**
     * Check if user has superuser access.
     *
     * @throws InvalidRequestException
     */
    public function hasSuperUserAccess(array $optional = []): object|bool
    {
        return $this->_request('UsersManager.hasSuperUserAccess', [], $optional);
    }

    /**
     * Get a list of users with superuser access.
     *
     * @throws InvalidRequestException
     */
    public function getUsersHavingSuperUserAccess(array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getUsersHavingSuperUserAccess', [], $optional);
    }

    /**
     * Update a user.
     *
     * @param  string  $userLogin Username
     * @param  string  $password Password in clear text
     *
     * @throws InvalidRequestException
     */
    public function updateUser(string $userLogin, string $password = '', string $email = '', string $alias = '', array $optional = []): object|bool
    {
        return $this->_request('UsersManager.updateUser', [
            'userLogin' => $userLogin,
            'password' => $password,
            'email' => $email,
            'alias' => $alias,
        ], $optional);
    }

    /**
     * Delete a user.
     *
     * @param  string  $userLogin Username
     *
     * @throws InvalidRequestException
     */
    public function deleteUser(string $userLogin, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.deleteUser', [
            'userLogin' => $userLogin,
        ], $optional);
    }

    /**
     * Checks if a user exist.
     *
     *
     * @throws InvalidRequestException
     */
    public function userExists(string $userLogin, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.userExists', [
            'userLogin' => $userLogin,
        ], $optional);
    }

    /**
     * Checks if a user exist by email.
     *
     * @throws InvalidRequestException
     */
    public function userEmailExists(string $userEmail, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.userEmailExists', [
            'userEmail' => $userEmail,
        ], $optional);
    }

    /**
     * Grant access to multiple sites.
     *
     * @param  string  $userLogin Username
     *
     * @throws InvalidRequestException
     */
    public function setUserAccess(string $userLogin, string $access, array $idSites, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.setUserAccess', [
            'userLogin' => $userLogin,
            'access' => $access,
            'idSites' => $idSites,
        ], $optional);
    }

    /**
     * Get the token for a user.
     *
     * @param  string  $userLogin Username
     * @param  string  $md5Password Password in clear text
     *
     * @throws InvalidRequestException
     */
    public function getTokenAuth(string $userLogin, string $md5Password, array $optional = []): object|bool
    {
        return $this->_request('UsersManager.getTokenAuth', [
            'userLogin' => $userLogin,
            'md5Password' => md5($md5Password),
        ], $optional);
    }
}
