<?php

namespace Effectra\ThirdParty;

/**
 * Interface OAuthServiceInterface
 *
 * This interface defines the contract for an OAuth service.
 */
interface OAuthServiceInterface extends OAuthConfig
{
    /**
     * Get the configuration array for the OAuth service.
     *
     * @return array The configuration array.
     */
    public function getConfig(): array;

    /**
     * Get the authorization URL for the OAuth service.
     *
     * @return string The authorization URL.
     */
    public function getAuthURL(): string;

    /**
     * Get the access token for the OAuth service using the authorization code.
     *
     * @param string $code The authorization code.
     * @return string The access token.
     */
    public function getAccessToken(string $code): string;

    /**
     * Get the user data from the OAuth service using the access token.
     *
     * @param string $token The access token.
     * @return array|null The user data array or null if unsuccessful.
     */
    public function getUser(string $token): ?array;
}
