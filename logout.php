<?php
	
	session_start();
	
	if(isset($_SESSION['u_id'])){
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
	setcookie('u_id', '', time()-3600);
	setcookie('u_name', '', time()-3600);
	
	header('Location: http://localhost/g/mygene.php');
?>
