<?php
/**
 * Bootstrap
 *
 * Bootstrap file for twitter bot.
 *
 * @category Components
 * @package  None
 * @author   T <dpdd@squat.net>
 * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @link     https://radarr-twitter-bot.duckdns.org
 * @since    1.0.0
 * @version  7.4
 */

require '../vendor/autoload.php';

class Sendarr
{

    function __construct()
    {
        $this->getEnvVars();
    }

    function getEnvVars() : void
    {
        $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
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

    function getTweetContent() : string
    {
        $request = strtolower($_SERVER['REQUEST_URI']);

        switch ($request) {
        case '/radarr':
            return $this->loadContentFromService('Radarr');
                break;
        case '/sonarr':
            return $this->loadContentFromService('Sonarr');
                break;
        }
    }

    function loadContentFromService(string $filename) : string
    {
        $path = __DIR__ . '/services/' . $filename . '.php';

        if (!file_exists($path)) {
            return "Service file doesnt exist";
        }
        
        return include $path;
    }

    function getTwitterConnection() : Abraham\TwitterOAuth\TwitterOAuth
    {
        return new Abraham\TwitterOAuth\TwitterOAuth(
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

        $status = $this->getTweetContent();
        
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

(new Sendarr)->tweet();