<?php
require_once "vendor/autoload.php";
require_once "config.php";

use LinkedIn\LinkedIn;

$config = array(
    'api_key' => API_KEY,
    'api_secret' => API_SECRET,
    'callback_url' => CALLBACK_URL
);
$linkedin = new LinkedIn($config);

session_start();
if(empty($_SESSION['token'])) {
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
$linkedin->setAccessToken($_SESSION['token']);
try {
    $people = $linkedin->get('people/~/connections');
} catch (\Exception $exception) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

header('Content-Type: application/json');
echo json_encode($people);
exit;