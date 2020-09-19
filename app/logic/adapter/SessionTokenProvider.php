<?php

namespace App\Logic\Adapter;

use Pecee\Http\Middleware\BaseCsrfVerifier;
use Pecee\Http\Security\ITokenProvider;

class SessionTokenProvider extends BaseCsrfVerifier implements ITokenProvider
{
    /**
     * CSRF validation will be ignored on the following urls.
     */
    protected $except = ['/api/*'];

    /**
     * Refresh existing token
     * @param string $name
     * @throws \Exception
     */
    public function refresh(string $name = null): void
    {
        csrf()->regenerateToken($name);
    }

    /**
     * Validate valid CSRF token
     *
     * @param string $token
     * @param string|null $name
     * @return bool
     * @throws \Exception
     */
    public function validate(string $token, string $name = null): bool
    {
        return csrf()->validate($token, $name);
    }

    /**
     * Get token token
     *
     * @param string|null $defaultValue
     * @param string|null $name
     * @return string|null
     * @throws \Exception
     */
    public function getToken(?string $defaultValue = null, string $name = null): ?string
    {
        return csrf()->getToken($name) ?? $defaultValue;
    }
}
