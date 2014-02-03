<?php
/**
 * Created by PhpStorm.
 * User: sedat
 * Date: 2/3/14
 * Time: 9:32 AM
 */
require_once "vendor/autoload.php";
require_once "config.php";

use LinkedIn\LinkedIn;

$config = array(
    'api_key' => API_KEY,
    'api_secret' => API_SECRET,
    'callback_url' => CALLBACK_URL
);
$linkedin = new LinkedIn($config);

$authCode = $_GET['code'];
try {
    $token = $linkedin->getAccessToken($authCode);
} catch (\Exception $exception) {
    $scope = array(
        'r_basicprofile',
        'r_network',
        'r_fullprofile',
        'r_contactinfo'
    );
    $state='online';
    $loginUrl = $linkedin->getLoginUrl($scope, $state);
    header("Location: " . $loginUrl);
    exit;
}

session_start();
$_SESSION['token'] = $token;
header('Location: index.php');
exit;