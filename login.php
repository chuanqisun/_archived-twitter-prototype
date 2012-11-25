<?php

	//start session
	session_start();
	
	//Clear error message
	$error_msg="";

	//if not logged in
	if (!isset($_SESSION['id'])){
		if (isset($_POST['submit'])){

			//connect to database
			$dbc=mysqli_connect('localhost', 'root', 'testpassword', 'g_prototype') or die('Error connecting to MySQL database');
	
			//extract data from the form
			$username=$_POST['username'];
			$password=$_POST['password'];

			if (!empty($username) && !empty($password)){
				$query="SELECT id, username FROM users WHERE username='$username' AND password=SHA('$password')";
				$data=mysqli_query($dbc, $query);

				if (mysqli_num_rows($data)==1) {
					$row=mysqli_fetch_array($data);
					$_SESSION['id'] = $row['id']; 
					$_SESSION['username'] = $row['username'];

					setcookie('id', $row['id'], time() + (60*60*24*30)); 
					setcookie('username', $row['username'], time() + (60*60*24*30));

					header('Location: http://localhost/g/mygene.php');
					
				}
				else{
				$error_msg= 'invalid username or password.';
				}
			}
			else{
				$error_msg='must enter username and passowrd.';
			}
		}
	}


	//if login failed
	if (empty($_SESSION['id'])) {
		echo '<p class=error">'.$error_msg.'</p>';
	}
	//if login success
	else{
		echo'<p class="login">You are logged in as ' . $_SESSION['username'] . '.</p>';
	}
?>
