<?php

namespace Sendarr;

class Bootstrap
{
    function __construct()
    {
        $this->getEnvVars();
    }

    function getEnvVars() : void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->load();
        $dotenv->required(
            [
                'twitter_api_key',
                'twitter_api_secret',
                'twitter_access_token',
                'twitter_access_token_secret'
            ]
        )->notEmpty();
    }
}