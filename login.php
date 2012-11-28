<?php

	require_once('connectvars.php');

	//start session
	session_start();
	
	//Clear error message
	$error_msg="";

	//if not logged in
	if (!isset($_SESSION['u_id'])){
		if (isset($_POST['submit'])){

			//connect to database
			$dbc=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to MySQL database');
	
			//extract data from the form
			$u_name=$_POST['u_name'];
			$u_pass=$_POST['u_pass'];

			//sanitize ---------this doesn't work--------------
			//$username=mysql_real_escape_string($dbc, trim($username));
			//$password=mysql_real_escape_string($dbc, trim($password));

			if (!empty($u_name) && !empty($u_pass)){
				$query="SELECT u_id, u_name FROM users WHERE u_name='$u_name' AND u_pass=SHA('$u_pass')";
				$data=mysqli_query($dbc, $query);

				if (mysqli_num_rows($data)==1) {
					$row=mysqli_fetch_array($data);
					$_SESSION['u_id'] = $row['u_id']; 
					$_SESSION['u_name'] = $row['u_name'];

					setcookie('u_id', $row['u_id'], time() + (60*60*24*30)); 
					setcookie('u_name', $row['u_name'], time() + (60*60*24*30));

					$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
					header('Location: ' . $home_url);
					
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
	if (empty($_SESSION['u_id'])) {
		echo '<p class=error">'.$error_msg.'</p>';
	}
	//if login success
	else{
		echo'<p class="login">You are logged in as ' . $_SESSION['u_name'] . '.</p>';
	}
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset>
		<legend>Log In</legend>
		<label>Username:</label>
		<input type="text" name="u_name" />
		<label>Password:</label>
		<input type="password" name="u_pass" />
	</fieldset>
	<input type="submit" value="Log In" name="submit" />
</form>
