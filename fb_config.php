<?php
if(!session_id())
{
    session_start();
}
 
// Include the autoloader provided in the SDK
require_once __DIR__ . '/vendor/autoload.php';
// Include required libraries
 
use Facebook\Facebook;
 
use Facebook\Exceptions\FacebookResponseException;
 
use Facebook\Exceptions\FacebookSDKException;
 
 
/* here you need to configure app detail*/
 
$appId         = "586918128384261"; //facebook app id
$appSecret     ="8ad11fd1cf21b54be680d8f9c4258098"; //facebook app secret

$redirectURL   = 'http://localhost/LoginWithPhp/index.php'; 
 
//Callback URL

 
$fb = new Facebook(array(
 
    'app_id' => $appId,
 
    'app_secret' => $appSecret,
 
    'default_graph_version' => 'v2.7',
 
));
 
 
// here you will be redirect to helper
 
$helper = $fb->getRedirectLoginHelper();
 
 
 
// Try to get access token
 
try {
    if(isset($_SESSION['facebook_access_token']))
    {
 
        $accessToken = $_SESSION['facebook_access_token'];
 
    }
    else
    {
 
          $accessToken = $helper->getAccessToken();
 
    }
 
} 
catch(FacebookResponseException $e) 
{
     echo 'Graph returned an error: ' . $e->getMessage();
      exit;
 
} catch(FacebookSDKException $e) 
{
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
}
 
?>