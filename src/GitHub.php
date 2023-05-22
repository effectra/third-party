<?php

namespace Effectra\ThirdParty;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Represents GitHub OAuth configuration and functionality.
 */
class GitHub extends OAuthConfig
{
    /**
     * @var Client The HTTP client.
     */
    protected Client $client;

    /**
     * The authorization URL for GitHub OAuth.
     */
    const AUTH_URL = 'https://github.com/login/oauth/authorize';

    /**
     * The token URL for GitHub OAuth.
     */
    const TOKEN_URL = 'https://github.com/login/oauth/access_token';

    /**
     * The user info URL for GitHub OAuth.
     */
    const USER_URL = 'https://api.github.com/user';

    /**
     * The default scopes for GitHub OAuth.
     *
     * @var array
     */
    public array $scopes = [
        'user',
    ];

    /**
     * GitHub constructor.
     *
     * @param string $client_id The client ID.
     * @param string $client_secret The client secret.
     * @param string $redirect_url The redirect URL.
     * @param array $scopes The array of scopes.
     */
    public function __construct(string $client_id, string $client_secret, string $redirect_url = '', array $scopes = [])
    {
        if (empty($scopes)) {
            $scopes = $this->scopes;
        }
        parent::__construct($client_id, $client_secret, $redirect_url, $scopes);
        $this->client = new Client();
    }

    /**
     * Get the GitHub OAuth configuration as an array.
     *
     * @return array The GitHub OAuth configuration array.
     */
    public function getConfig(): array
    {
        return [
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'redirect_uri' => $this->getRedirectURL(),
            'scope' => $this->withScopes($this->scopes)->getScopesString(),
            'state' => $this->generateToken(15),
            'response_type' => 'code',
        ];
    }

    /**
     * Get the GitHub OAuth authorization URL.
     *
     * @return string The authorization URL.
     */
    public function getAuthURL(): string
    {
        $params = $this->onlyConfig([
            'response_type',
            'client_id',
            'redirect_uri',
            'scope',
            'state',
        ]);

        return $this->buildUrl(self::AUTH_URL, $params);
    }

    /**
     * Get the access token using the authorization code.
     *
     * @param string $code The authorization code.
     * @return string The access token.
     */
    public function getAccessToken(string $code): string
    {
        try {
            $params = array_merge(
                ['code' => $code],
                $this->onlyConfig([
                    'client_id',
                    'client_secret',
                    'redirect_uri',
                    'state',
                ])
            );

            $response = $this->client->post(self::TOKEN_URL, [
                'form_params' => $params,
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return $data['access_token'] ?? '';
        } catch (ClientException $e) {
            return '';
        }
    }

    /**
     * Get the user information using the access token.
     *
     * @param string $token The access token.
     * @return array|null The user information array or null if an error occurred.
     */
    public function getUser(string $token): ?array
    {
        try {
            $response = $this->client->get(self::USER_URL, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'User-Agent' => 'MyGitHubApp',
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (ClientException $e) {
            return null;
        }
    }
}
