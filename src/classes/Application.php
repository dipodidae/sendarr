<?php

namespace Sendarr;
use Sendarr\Service as Service;

class Application
{
    function getStatus() : string
    {
        $request = strtolower($_SERVER['REQUEST_URI']);

        switch ($request) {
        case '/radarr':
            return (new Service\Radarr)->getStatus();
            break;
        case '/sonarr':
            return (new Service\Sonarr)->getStatus();
            break;
        case '/rest':
            return (new Service\Rest)->getStatus();
            break;
        default:
            return '';
        }
    }


    function getTwitterConnection()
    {
        return new \Abraham\TwitterOAuth\TwitterOAuth(
            $_ENV['twitter_api_key'],
            $_ENV['twitter_api_secret'],
            $_ENV['twitter_access_token'],
            $_ENV['twitter_access_token_secret']
        );
    }

    function sendHeader(int $status) : void
    {
        header($_SERVER['SERVER_PROTOCOL'] . strval($status), true, $status);
    }

    function tweet() : void
    {

        $status = $this->getStatus();
        
        switch($_ENV['environment']) {
        case 'test':
            $this->sendHeader(200);
            var_dump($status);
            break;
            
        case 'live':
            if (empty($status)) {
                $this->sendHeader(500);
                echo "Tweet content was empty";
            } else {
                try {
                    $connection = $this->getTwitterConnection();    
                    $connection->get("account/verify_credentials");
                    $connection->post("statuses/update", ["status" => $status]);
                    $this->sendHeader(200);
                    echo "Tweet posted";
                } catch (Exception $error) {
                    $this->sendHeader(500);
                    echo "Error tweeting";
                }
            }
            break;
        }
    }
}