<?php

namespace App\Logic\Adapters;

use Pecee\Http\Security\ITokenProvider;
use Sim\Cookie\Exceptions\CookieException;
use Sim\Cookie\SetCookie;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;

class SessionTokenProvider implements ITokenProvider
{
    const CSRF_KEY = 'CSRF-TOKEN';

    /**
     * Refresh existing token
     * @throws ConfigNotRegisteredException
     * @throws CookieException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \Exception
     */
    public function refresh(): void
    {
        $token = \csrf()->regenerateToken();
        $csrfExpiration = \config()->get('csrf.expiration') ?? 0;
        $csrfExpiration = 0 !== $csrfExpiration ? time() + $csrfExpiration : $csrfExpiration;
        \cookie()->set(new SetCookie(self::CSRF_KEY, $token, $csrfExpiration, '/'));
    }

    /**
     * Validate valid CSRF token
     *
     * @param string $token
     * @return bool
     * @throws \Exception
     */
    public function validate(string $token): bool
    {
        $token = \cookie()->prepareGetCookieValue($token);
        return \csrf()->validate($token);
    }

    /**
     * Get token token
     *
     * @param string|null $defaultValue
     * @return string|null
     * @throws CookieException
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     * @throws \Exception
     */
    public function getToken(?string $defaultValue = null): ?string
    {
        $token = \csrf()->getToken() ?? $defaultValue;
        $csrfExpiration = \config()->get('csrf.expiration') ?? 0;
        $csrfExpiration = 0 !== $csrfExpiration ? time() + $csrfExpiration : $csrfExpiration;
        \cookie()->set(new SetCookie(self::CSRF_KEY, $token, $csrfExpiration, '/'));
        return $token;
    }
}
