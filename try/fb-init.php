<?php
session_start();

$fbappid = "586918128384261"; 
$fbappsecret = "8ad11fd1cf21b54be680d8f9c4258098"; 

require './vendor/autoload.php';

$fb = new Facebook\Facebook(
    array(
        'app_id' => $fbappid, 
        'app_secret' => $fbappsecret, 
        'default_graph_version' => 'v2.7', ));

$helper = $fb->getRedirectLoginHelper();
$login_url = $helper->getLoginUrl("http://localhost/LoginWithPhp/");
// print_r($login_url);

try{
    $accessToken = $helper->getAccessToken();
    if(isset($accessToken)){
        $_SESSION['access_token'] = (string)$accessToken;
        header('Location:index.php');



    }
}catch(Exception $e){
    echo $e->getTraceAsString();
}

// if($_SESSION['access_token'])