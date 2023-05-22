# Effectra ThirdParty Library

Effectra\ThirdParty is a PHP library that provides OAuth configuration and functionality for various third-party platforms such as LinkedIn, GitHub, Facebook, and Google. It simplifies the process of integrating with these platforms and accessing user data through OAuth authentication.

## Features

- Simplified OAuth configuration and authentication for third-party platforms.
- Easy retrieval of access tokens and user information.
- Supports multiple popular platforms like LinkedIn, GitHub, Facebook, and Google.

## Installation

You can install the Effectra\ThirdParty library via Composer. Run the following command in your project directory:

```shell
composer require effectra/third-party
```

## Usage

### LinkedIn

To use the LinkedIn OAuth functionality, follow these steps:

1. Create an instance of the `LinkedIn` class by providing your LinkedIn client ID, client secret, and optional redirect URL and scopes.

```php
use Effectra\ThirdParty\LinkedIn;

$linkedin = new LinkedIn('YOUR_CLIENT_ID', 'YOUR_CLIENT_SECRET', 'YOUR_REDIRECT_URL', ['r_liteprofile', 'r_emailaddress']);
```

2. Generate the authorization URL to redirect the user for authentication:

```php
$authUrl = $linkedin->getAuthURL();
```

3. Redirect the user to the generated authorization URL. After successful authentication, LinkedIn will redirect the user back to the specified redirect URL with an authorization code.

4. Exchange the authorization code for an access token:

```php
$code = $_GET['code']; // The authorization code obtained from the LinkedIn redirect
$accessToken = $linkedin->getAccessToken($code);
```

5. Use the access token to retrieve user information:

```php
$user = $linkedin->getUser($accessToken);
```

### GitHub

To use the GitHub OAuth functionality, follow these steps:

1. Create an instance of the `GitHub` class by providing your GitHub client ID, client secret, and optional redirect URL and scopes.

```php
use Effectra\ThirdParty\GitHub;

$github = new GitHub('YOUR_CLIENT_ID', 'YOUR_CLIENT_SECRET', 'YOUR_REDIRECT_URL', ['user']);
```

2. Generate the authorization URL to redirect the user for authentication:

```php
$authUrl = $github->getAuthURL();
```

3. Redirect the user to the generated authorization URL. After successful authentication, GitHub will redirect the user back to the specified redirect URL with an authorization code.

4. Exchange the authorization code for an access token:

```php
$code = $_GET['code']; // The authorization code obtained from the GitHub redirect
$accessToken = $github->getAccessToken($code);
```

5. Use the access token to retrieve user information:

```php
$user = $github->getUser($accessToken);
```

### Facebook

To use the Facebook OAuth functionality, follow these steps:

1. Create an instance of the `Facebook` class by providing your Facebook client ID, client secret, and optional redirect URL and scopes.

```php
use Effectra\ThirdParty\Facebook;

$facebook = new Facebook('YOUR_CLIENT_ID', 'YOUR_CLIENT_SECRET', 'YOUR_REDIRECT_URL', ['email']);
```

2. Generate the authorization URL to redirect the user for authentication:

```php
$authUrl = $facebook->getAuthURL();
```

3. Redirect the user to the generated authorization URL. After successful authentication, Facebook will redirect the user back to the specified redirect URL with an authorization code.

4. Exchange the authorization code for an access token:

```php
$code = $_GET['code']; // The authorization code obtained from the Facebook redirect
$accessToken = $facebook->getAccessToken($code);
```

5

. Use the access token to retrieve user information:

```php
$user = $facebook->getUser($accessToken);
```

### Google

To use the Google OAuth functionality, follow these steps:

1. Create an instance of the `Google` class by providing your Google client ID, client secret, and optional redirect URL and scopes.

```php
use Effectra\ThirdParty\Google;

$google = new Google('YOUR_CLIENT_ID', 'YOUR_CLIENT_SECRET', 'YOUR_REDIRECT_URL', ['profile', 'email']);
```

2. Generate the authorization URL to redirect the user for authentication:

```php
$authUrl = $google->getAuthURL();
```

3. Redirect the user to the generated authorization URL. After successful authentication, Google will redirect the user back to the specified redirect URL with an authorization code.

4. Exchange the authorization code for an access token:

```php
$code = $_GET['code']; // The authorization code obtained from the Google redirect
$accessToken = $google->getAccessToken($code);
```

5. Use the access token to retrieve user information:

```php
$user = $google->getUser($accessToken);
```

## OAuthServiceInterface



The `OAuthServiceInterface` is an interface that defines the contract for an OAuth service. It provides methods for retrieving configuration, authorization URL, access token, and user data from an OAuth service.

### Usage

To use this interface, you need to create a class that implements it and provides the necessary functionality. Here's an example of how you can implement the `OAuthServiceInterface`:

```php
<?php

namespace Effectra\ThirdParty;

class MyOAuthService implements OAuthServiceInterface
{
    // Implement the getConfig() method
    public function getConfig(): array
    {
        // Return the configuration array for the OAuth service
    }

    // Implement the getAuthURL() method
    public function getAuthURL(): string
    {
        // Return the authorization URL for the OAuth service
    }

    // Implement the getAccessToken() method
    public function getAccessToken(string $code): string
    {
        // Get the access token for the OAuth service using the authorization code
    }

    // Implement the getUser() method
    public function getUser(string $token): ?array
    {
        // Get the user data from the OAuth service using the access token
    }
}
```

In the above example, you need to replace the placeholder methods with your actual implementation based on the OAuth service you are integrating with.

### Method Overview

#### `getConfig(): array`

This method returns the configuration array for the OAuth service.

#### `getAuthURL(): string`

This method returns the authorization URL for the OAuth service.

#### `getAccessToken(string $code): string`

This method retrieves the access token for the OAuth service using the authorization code provided as a parameter.

#### `getUser(string $token): ?array`

This method retrieves the user data from the OAuth service using the access token provided as a parameter. It returns an array containing user data or `null` if the operation is unsuccessful.


## License

This library is open source and available under the [MIT License](LICENSE).

## Contribution

Contributions are welcome! If you encounter any issues or have suggestions for improvements, please feel free to open an issue or submit a pull request.

## Credits

This library is developed and maintained by Effectra. You can find more information about us on our website: [www.effectra.com](https://www.effectra.com/)

## Contact

For any inquiries or questions, you can reach us at [info@effectra.com](mailto:info@effectra.com)

---

Feel free to modify and customize this README file according to your specific needs.