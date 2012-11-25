<?php
	
	session_start();
	
	if(isset($_SESSION['id'])){
		//delete session variables
		$_SESSION=array();
	}

	//delete session cookie
	if(isset($_SESSION[session_name()])) {
		setcookie(session_name(), '', time()-3600);
	}
	
	//destroy session
	session_destroy();

	//delete user cookie
	setcookie('id', '', time()-3600);
	setcookie('username', '', time()-3600);
	
	header('Location: http://localhost/g/login.html');
?>
