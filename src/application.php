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

new \Sendarr\Bootstrap;

(new \Sendarr\Application)->tweet();