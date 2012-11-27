<?php
	session_start();

	//auto re-login by cookie
	if(!isset($_SESSION['u_id'])){
		if(isset($_COOKIE['u_id']) && isset($_COOKIE['u_name'])){
			$_SESSION['u_id']=$_COOKIE['u_id'];
			$_SESSION['u_name']=$_COOKIE['u_name'];
		}
	}
?>
