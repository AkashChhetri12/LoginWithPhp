<?php 
// Include FB config file && User class
require_once 'fb_config.php';
require_once 'user_config.php';
if(isset($accessToken)){
    if(isset($_SESSION['facebook_access_token'])){
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }else{
        // Put short-lived access token in session
        $_SESSION['facebook_access_token'] = (string) $accessToken;
        
          // OAuth 2.0 client handler helps to manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();
        
        // Exchanges a short-lived access token for a long-lived one
        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
        $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
        
        // Set default access token to be used in script
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
        // print_r($_SESSION['facebook_access_token']);
    }
    
    // Redirect the user back to the same page if url has "code" parameter in query string
    if(isset($_GET['code'])){
        header('Location: ./');
    }
    
    // Getting user facebook profile info
    try {
        $profileRequest = $fb->get('/me?fields=name,first_name,last_name,email,picture');
        $fbUserProfile = $profileRequest->getGraphNode()->asArray();
        // print_r($fbUserProfile);
    } catch(FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        session_destroy();
        // Redirect user back to app login page
        header("Location: ./");
        exit;
    } catch(FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
    
    // Initialize User class
    $user = new User();
    
    // Insert or update user data to the database
    $fbUserData = array(
        'oauth_provider'=> 'facebook',
        'oauth_uid'     => $fbUserProfile['id'],
        'first_name'    => $fbUserProfile['first_name'],
        'last_name'     => $fbUserProfile['last_name'],
        'email'         => $fbUserProfile['email'],
        'picture'       => $fbUserProfile['picture']['url'],
    );
    // print_r($fbUserData);
    $userData = $user->checkUser($fbUserData);
    // print_r($usersData);
    
    // Put user data into session
    $_SESSION['userData'] = $userData;
    
    // Get logout url
    // $logoutURL = $helper->getLogoutUrl($accessToken, $redirectURL.'logout.php');
    // print_r($logoutURL);
    
    // Render facebook profile data
    // print_r($userData);
    if(!empty($userData)){
        $output  = '<h2 style="color:#999999;">Facebook Profile Details</h2>';
        $output .= '<div style="position: relative;">';
        $output .= '<img style="position: absolute; top: 90%; left: 25%;" src="'.$userData['picture'].'"/>';
        $output .= '</div>';
        $output .= '<br/>Facebook ID : '.$userData['oauth_uid'];
        $output .= '<br/>Name : '.$userData['first_name'].' '.$userData['last_name'];
        $output .= '<br/>Email : '.$userData['email'];
        $output .= '<br/>Logged in with : Facebook';
        $output .= '<br/>Logout from <a href="./logout.php">Facebook</a>'; 

        // print_r($output);
        // header("Location:index.php");
    }
    else{
        $output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
    }
    
}else{
    // Get login url
    $loginURL = $helper->getLoginUrl($redirectURL);
    // print_r($loginURL);
    
    // Render facebook login button
    $output = '<a href="'.htmlspecialchars($loginURL).'"><img src="images/login-button-png.png"></a>';
    // $output = '<a href="'.htmlspecialchars($loginURL).'"></a>';
    // echo $output;
}

?>
<!DOCTYPE html>
    <html lang="en">  
    <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>
    <body>
        <br><br><br>
    <div class="container">
    <h2 class="alert alert-info">facebook login in php</h2>
    <div class="row">
    <div><?php echo $output; ?> </div>
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</body>
</html>