<?php

namespace Effectra\ThirdParty;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Represents LinkedIn OAuth configuration and functionality.
 */
class LinkedIn extends OAuthConfig
{
    /**
     * @var Client The HTTP client.
     */
    protected Client $client;

    /**
     * The authorization URL for LinkedIn OAuth.
     */
    const AUTH_URL = 'https://www.linkedin.com/oauth/v2/authorization';

    /**
     * The token URL for LinkedIn OAuth.
     */
    const TOKEN_URL = 'https://www.linkedin.com/oauth/v2/accessToken';

    /**
     * The user info URL for LinkedIn OAuth.
     */
    const USER_URL = 'https://api.linkedin.com/v2/me?projection=(id,firstName,lastName,profilePicture(displayImage~:playableStreams))';

    /**
     * The default scopes for LinkedIn OAuth.
     *
     * @var array
     */
    public array $scopes = [
        'r_liteprofile',
        'r_emailaddress',
    ];

    /**
     * LinkedIn constructor.
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
     * Get the LinkedIn OAuth configuration as an array.
     *
     * @return array The LinkedIn OAuth configuration array.
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
            'grant_type' => 'authorization_code'
        ];
    }

    /**
     * Get the LinkedIn OAuth authorization URL.
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
                    'grant_type'
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
     * @return array|null The user information array or null if an error occurred.
     */
    public function getUser(string $token): ?array
    {
        try {
            $response = $this->client->get(self::USER_URL, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (ClientException $e) {
            return null;
        }
    }
}
