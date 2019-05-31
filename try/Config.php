<?php
session_start();
// ##### DB Configuration #####
$servername = "localhost";
$username = "root";
$password = "";
$db = "person";
// $token="";
// ##### FB App Configuration #####
$fbPermissions = ['email']; 
$fbappid = "586918128384261"; 
$fbappsecret = "8ad11fd1cf21b54be680d8f9c4258098"; 
$redirectURL = "http://localhost/LoginWithPhp/authenticate.php"; 
// $redirectURL = "YourRedirectUrl"; 
 
 
// ##### Create connection #####
$conn = new mysqli($servername, $username, $password, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// ##### Required Library #####
require_once __DIR__ . '/vendor/facebook/graph-sdk/src/Facebook/autoload.php';
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
 
$fb = new Facebook(array('app_id' => $fbappid, 'app_secret' => $fbappsecret, 'default_graph_version' => 'v2.6', ));
$helper = $fb->getRedirectLoginHelper();
// ##### Check facebook token stored or get new access token #####
try {
    if(isset($_SESSION['fb_token'])){
        $accessToken = $_SESSION['fb_token'];
    }else{
        $accessToken = $helper->getAccessToken();
    }
} catch(FacebookResponseException $e) {
    echo 'Facebook Responser error: ' . $e->getMessage();
    exit;
} catch(FacebookSDKException $e) {
    echo 'Facebook SDK error: ' . $e->getMessage();
    exit;
}
?>