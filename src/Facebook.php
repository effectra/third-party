<?php

namespace Effectra\ThirdParty;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Represents Facebook OAuth configuration and functionality.
 */
class Facebook extends OAuthConfig
{
    /**
     * @var Client The HTTP client.
     */
    protected Client $client;

    /**
     * The authorization URL for Facebook OAuth.
     */
    const AUTH_URL = 'https://www.facebook.com/v12.0/dialog/oauth';

    /**
     * The token URL for Facebook OAuth.
     */
    const TOKEN_URL = 'https://graph.facebook.com/v12.0/oauth/access_token';

    /**
     * The user info URL for Facebook OAuth.
     */
    const USER_URL = 'https://graph.facebook.com/me?fields=id,name,email';

    /**
     * The default scopes for Facebook OAuth.
     *
     * @var array
     */
    public array $scopes = [
        'email',
    ];

    /**
     * Facebook constructor.
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
     * Get the Facebook OAuth configuration as an array.
     *
     * @return array The Facebook OAuth configuration array.
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
            'grant_type' => 'authorization_code',
        ];
    }

    /**
     * Get the Facebook OAuth authorization URL.
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
                    'grant_type',
                ])
            );

            $response = $this->client->post(self::TOKEN_URL, ['form_params' => $params]);

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
     * @return array|null The user information array, or null if an error occurred.
     */
    public function getUser(string $token): ?array
    {
        try {
            $decoded = urlencode($token);
            $url = self::USER_URL . '?' . http_build_query(['access_token' => $decoded]);

            $response = $this->client->get($url);

            return json_decode($response->getBody(), true);

        } catch (ClientException $e) {
            return null;
        }
    }
}
