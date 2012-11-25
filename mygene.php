<?php
	session_start();

	//auto re-login by cookie
	if(!isset($_SESSION['id'])){
		if(isset($_COOKIE['id']) && isset($_COOKIE['username'])){
			$_SESSION['id']=$_COOKIE['id'];
			$_SESSION['username']=$_COOKIE['username'];
		}
	}
	
	if(isset($_SESSION['username'])){
		echo 'Welcome '. $_SESSION['username']. '<br />';
		echo '<a href="logout.php">Log Out</a>';
	}
	else{
		echo '<a href="login.html">Log In</a><br />';
		echo '<a href="signup.html">Sign Up</a>';
	}
?>
