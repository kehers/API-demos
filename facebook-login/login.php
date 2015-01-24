<?php
// Require the facebook php sdk
require 'facebook/facebook.php';

// Set the app id and secret
define('ID', '__app_id_here__');
define('SECRET', '__app_secret_here__');

// Init sdk
$facebook = new Facebook(array(
    'appId'  => ID,
    'secret' => SECRET,
));

// Authenticated?
$fbuser = $facebook->getUser();
if ($fbuser) {
    try {
		// Get user details
        $data = $facebook->api('/me');
		
        $id = $data['id'];
        $username = $data['username'];
        $name = $data['name'];
        $avatar = 'http://graph.facebook.com/'.$data['id'].'/picture';
		
		// Valid login,
		// Do something here...
		?>
		<p><img src="<?php echo $avatar; ?>" width="120" /></p>
		<p>Hey <?php echo $name; ?></p>
		<?php
    } catch (FacebookApiException $e) {
        $fbuser = null;
    }
}

// Not authenticated,
// ...redirect to facebook to authenticate
if(!$fbuser) {
    header("location:".$facebook->getLoginUrl());
    exit;
}
?>