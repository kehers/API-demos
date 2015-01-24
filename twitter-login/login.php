<?php
require_once 'twitteroauth/twitteroauth.php';

define('KEY', '__consumer_key_here__');
define('SECRET', '__consumer_secret_here__');
define('CALLBACK', 'http://localhost/twitter_login/login.php'); // Absolute url to this file

// Returning from Twitter for authentication
if(isset($_REQUEST['oauth_token'])) {
	// Init lib
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
	// Unathenticated
	// Init lib
	$twitter = new TwitterOAuth(KEY, SECRET);

	// Request tokens from twitter
	$request_token = $twitter->getRequestToken(CALLBACK);

	// Save tokens for later
	$_SESSION['twitter_oauth_request_token'] = $token = $request_token['oauth_token'];
	$_SESSION['twitter_oauth_request_token_secret'] = $request_token['oauth_token_secret'];

	// Build the authorization URL
	$request_link = $twitter->getAuthorizeURL($token);
	header("location:$request_link");
	exit;
}
?>