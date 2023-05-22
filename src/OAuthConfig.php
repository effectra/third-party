<?php

namespace Effectra\ThirdParty;

/**
 * Class OAuthConfig
 * Represents the configuration for OAuth authentication.
 */
class OAuthConfig
{
    /**
     * @var string The client ID.
     */
    protected string $client_id;

    /**
     * @var string The client secret.
     */
    protected string $client_secret;

    /**
     * @var string The redirect URL.
     */
    protected string $redirect_url;

    /**
     * @var array The array of scopes.
     */
    protected array $scopes = [];

    /**
     * OAuthConfig constructor.
     *
     * @param string $client_id The client ID.
     * @param string $client_secret The client secret.
     * @param string $redirect_url The redirect URL.
     * @param array $scopes The array of scopes.
     */
    public function __construct(string $client_id, string $client_secret, string $redirect_url = '', array $scopes = [])
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_url = $redirect_url;
        $this->scopes = $scopes;
    }

    /**
     * Get the OAuth configuration as an array.
     *
     * @return array The OAuth configuration array.
     */
    public function getConfig(): array
    {
        return [
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'redirect_uri' => $this->getRedirectURL(),
            'scope' => $this->getScopesString(),
            'state' => $this->generateToken(),
        ];
    }

    /**
     * Get the client ID.
     *
     * @return string The client ID.
     */
    public function getClientId(): string
    {
        return $this->client_id;
    }

    /**
     * Get the client secret.
     *
     * @return string The client secret.
     */
    public function getClientSecret(): string
    {
        return $this->client_secret;
    }

    /**
     * Get the redirect URL.
     *
     * @return string The redirect URL.
     */
    public function getRedirectURL(): string
    {
        return $this->redirect_url;
    }

    /**
     * Get the array of scopes.
     *
     * @return array The array of scopes.
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * Get a specific scope by name.
     *
     * @param string $name The name of the scope.
     * @return array The scope.
     */
    public function getScope(string $name): array
    {
        return $this->scopes[$name];
    }

    /**
     * Get the scopes as a string.
     *
     * @param array|string $separator The separator to use between scopes. Defaults to a space.
     * @return string The scopes as a string.
     */
    public function getScopesString(array|string $separator = " "): string
    {
        return join($separator, $this->scopes);
    }

    /**
     * Set a new client ID and return a new instance of OAuthConfig.
     *
     * @param string $client_id The new client ID.
     * @return self The new instance of OAuthConfig.
     */
    public function withClientId(string $client_id): self
    {
        $clone = clone $this;
        $clone->client_id = $client_id;
        return $clone;
    }

    /**
     * Set a new client secret and return a new instance of OAuthConfig.
     *
     * @param string $client_secret The new client secret.
     * @return self The new instance of OAuthConfig.
     */
    public function withClientSecret(string $client_secret): self
    {
        $clone = clone $this;
        $clone->client_secret = $client_secret;
        return $clone;
    }

    /**
     * Set a new redirect URL and return a new instance of OAuthConfig.
     *
     * @param string $redirect_url The new redirect URL.
     * @return self The new instance of OAuthConfig.
     */
    public function withRedirectURL(string $redirect_url): self
    {
        $clone = clone $this;
        $clone->redirect_url = trim($redirect_url, '/');
        return $clone;
    }

    /**
     * Set new scopes and return a new instance of OAuthConfig.
     *
     * @param array $scopes The new array of scopes.
     * @return self The new instance of OAuthConfig.
     */
    public function withScopes(array $scopes): self
    {
        $clone = clone $this;
        $clone->scopes = $scopes;
        return $clone;
    }

    /**
     * Get a new configuration without specified keys.
     *
     * @param array|string $key The key(s) to exclude from the configuration.
     * @return array The new configuration without the specified keys.
     */
    public function withoutConfig(array|string $key): array
    {
        if (is_string($key)) {
            $key = [$key];
        }
        $config = [];
        foreach ($this->getConfig() as $config_key => $config_value) {
            if (!in_array($config_key, $key)) {
                $config[$config_key] = $config_value;
            }
        }
        return $config;
    }

    /**
     * Get a new configuration with only specified keys.
     *
     * @param array|string $key The key(s) to include in the configuration.
     * @return array The new configuration with only the specified keys.
     */
    public function onlyConfig(array|string $key): array
    {
        if (is_string($key)) {
            $key = [$key];
        }
        $config = [];
        foreach ($this->getConfig() as $config_key => $config_value) {
            if (in_array($config_key, $key)) {
                $config[$config_key] = $config_value;
            }
        }
        return $config;
    }

    /**
     * Build a URL with the base and query parameters.
     *
     * @param string $base The base URL.
     * @param array $params The query parameters.
     * @return string The built URL.
     */
    public function buildUrl(string $base, array $params): string
    {
        return trim($base, '/') . '?' . http_build_query($params);
    }

    /**
     * Generate a random token.
     *
     * @param int $length The length of the token. Defaults to 10.
     * @return string The generated token.
     */
    public static function generateToken(int $length = 10): string
    {
        return bin2hex(random_bytes($length));
    }
}
