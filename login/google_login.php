<?php
session_start();
require_once '../vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('32837330900-ae47m83bdkc6dektln3du4v82cug1m56.apps.googleusercontent.com');
$client->setClientSecret('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');

$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : (isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : '/guardiashop/index.php');
if ($redirect) {
    $_SESSION['redirect_after_login'] = $redirect;
}

$client->setRedirectUri('http://localhost/guardiashop/login/google-callback.php');
$client->addScope("email");
$client->addScope("profile");

// ESTA ES LA FORMA CORRECTA:
$client->setPrompt('select_account');

$auth_url = $client->createAuthUrl();
header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
exit;
