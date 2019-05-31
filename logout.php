
<?php
// include FB config file
include_once 'fb_config.php';
// remove access token from session
unset($_SESSION['facebook_access_token']);
// remove user data from session
unset($_SESSION['userData']);
// Redirect to the homepage
header("Location:index.php");
?>