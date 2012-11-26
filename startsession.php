<?php
	session_start();

	//auto re-login by cookie
	if(!isset($_SESSION['id'])){
		if(isset($_COOKIE['id']) && isset($_COOKIE['username'])){
			$_SESSION['id']=$_COOKIE['id'];
			$_SESSION['username']=$_COOKIE['username'];
		}
	}
?>
