<?php
session_start();
require 'twitteroauth/twitteroauth.php';

define('KEY', '__consumer_key_here__');
define('SECRET', '__consumer_secret_here__');
// Remember, the callback should be an absolute URL
define('CALLBACK', 'http://localhost/twitter-login/login.php');

// Remember, twitter returns oauth_token, oauth_verifier and .... with the url
if(isset($_REQUEST['oauth_token'])) {
    // Init lib with tokens
    $twitter = new TwitterOAuth(KEY, SECRET, $_SESSION['twitter_oauth_request_token'], $_SESSION['twitter_oauth_request_token_secret']);

    // Request tokens from twitter
    $access_token = $twitter->getAccessToken($_REQUEST['oauth_verifier']);
    if ($access_token['screen_name']) {
        // Valid login
        echo 'Welcome '.$access_token['screen_name'];
    }
    else {
        // Some error message here...
        echo 'There has been an error verifying your identity.';
    }
}
else {
    $twitter = new TwitterOAuth(KEY, SECRET);
    
    // Get Request token from twitter
    $request_token = $twitter->getRequestToken(CALLBACK);
    
    // Save tokens for later
    $_SESSION['twitter_oauth_request_token'] = $token = $request_token['oauth_token'];
    $_SESSION['twitter_oauth_request_token_secret'] = $request_token['oauth_token_secret'];
    
    // Build the authentication URL
    $request_link = $twitter->getAuthorizeURL($token, true);
    // If we are not doing sign in with twitter, it'd have been
    // $request_link = $twitter->getAuthorizeURL($token);
    header("location:$request_link");
    exit;
}
?>