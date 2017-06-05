<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OauthSample</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<?php

include_once 'functions.php';
session_start();

$redirect_uri = 'http://cw51460.tmweb.ru/';

if (isset($_SESSION['token']))
{
    // MAIN activity

    MAIN($_SESSION['token'], $_SESSION['uid']);
}
else if ($_GET['code'])
{
        // get ACCESS_TOKEN and USER_ID

    $code = $_GET['code'];
    $USER_INFO = get_access_token($redirect_uri, $code);
    $ACCESS_TOKEN = $USER_INFO['access_token'];
    $UID = $USER_INFO['user_id'];

        // Set session info

    if (!isset($_SESSION['token'])) $_SESSION['token'] = $ACCESS_TOKEN;
    if (!isset($_SESSION['uid'])) $_SESSION['uid'] = $UID;

        // MAIN activity

    MAIN($_SESSION['token'], $_SESSION['uid']);

}
else if ($_GET['error'])
{
        // In case of authorisation error

    echo '<p class=\"error\"> Ошибка авторизации </p>';
}
else
{
        // Authorisation button show

    $auth_link = get_auth_link($redirect_uri);

    echo "<a class=\"auth\" href=\"" . $auth_link . "\"> Авторизоваться </a>";
}
?>

</body>
</html>