<?php

namespace Sendarr;

class Bootstrap
{
    function __construct()
    {
        $this->_setConstants();
        $this->_getEnvVars();
    }

    private function _getEnvVars() : void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(DIRECTORY_ROOT);
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

    private function _setConstants() : void 
    {

        define('DIRECTORY_ROOT', dirname(__DIR__, 2));
        define('DIRECTORY_LOG', DIRECTORY_ROOT . "/logs");
    }
}