<?php

namespace App\Providers;

use App\Foundation\Repository\AdminPassportRepository;
use League\OAuth2\Server\Grant\PasswordGrant;
use Laravel\Passport\PassportServiceProvider as BasePassportServiceProvider;
use Laravel\Passport\Passport;

class PassportAdminServiceProvider extends BasePassportServiceProvider
{
    /**
     * Create and configure a Password grant instance.
     *
     * @return PasswordGrant
     */
    protected function makePasswordGrant()
    {
        $grant = new PasswordGrant(
        //主要是这里，我们调用我们自己UserRepository
            $this->app->make(AdminPassportRepository::class),
            $this->app->make(\Laravel\Passport\Bridge\RefreshTokenRepository::class)
        );

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }

}