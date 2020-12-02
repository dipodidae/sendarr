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
        
if ($_ENV['environment'] === "test") {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

$request = strtolower($_SERVER['REQUEST_URI']);

$tweetContent = null;

switch ($request) {
case '/radarr':
    $tweetContent = include __DIR__ . '/services/Radarr.php';
    break;
}

$connection = new Abraham\TwitterOAuth\TwitterOAuth(
    $_ENV['twitter_api_key'],
    $_ENV['twitter_api_secret'],
    $_ENV['twitter_access_token'],
    $_ENV['twitter_access_token_secret']
);

$status = $connection->get("account/verify_credentials");

switch($_ENV['environment']) {
case 'test':
    var_dump($tweetContent);
    break;

case 'live':
    if (empty($tweetContent)) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
        echo "Tweet content was empty";
    } else {
        $connection->post("statuses/update", ["status" => $tweetContent]);
        header($_SERVER['SERVER_PROTOCOL'] . ' 200 Ok', true, 200);
        echo "Tweet posted";
    }
    break;
}
