<?php
	session_start();
	include 'functions.php';

	//log out function
	if(isset( $_GET['logout']) ){
		unset($_SESSION);
		header('getUserAccess.php');
	}

	if(!isset($_SESSION['fb_access_token']) && isset($_GET['code']) && isset($_GET['state']) && $_GET['state'] == 
		$_SESSION['fb_state'] )
	{
		if (file_exists(accessCodeTxt)){
			$file = accessCodeTxt;
			$currentContent = file_get_contents($file);
			$code = $_GET['code'];
			if($currentContent == $code){header(fbRedirectUri);}
			else{file_put_contents($file, $code);}
		}
		else{
			$file = fopen('accessCode.txt', 'w'); 
			header('Refresh:0');}
		
		$accessToken = getAccessTokenWithCode($_GET['code']);
		$_SESSION['fb_access_token'] = $accessToken['data'];

	} 
	elseif(!isset($_SESSION['fb_access_token'])){
		$_SESSION['fb_state'] = mt_rand(1, 1000);
		$fbLoginUrl = getFacebookLoginUrl('email, public_profile', $_SESSION['fb_state']);
	}
?>

<h1> Facebook Graph Api Get User Token </h1>
<hr />
	<h3>SESSION</h3>
	<pre><?php print_r($_SESSION); ?> </pre>
<hr />
<?php if(isset($fbLoginUrl)) :?>
	<a href = "<?php echo $fbLoginUrl; ?>" >
		Login With Facebook
	</a>
	<br /><br />
	href: <?php echo $fbLoginUrl; ?>
<?php else :?>
	<a href = "getUserAccess.php?logout=1">
		Logout
<?php endif ;?>	