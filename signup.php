<?php 
	
	//prepare empty error message
	$error_msg = "";
	if (isset($_POST['submit'])) {	
		require_once('connectvars.php');
		//connect to database
		$dbc=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to MySQL server.');

		//extract profile from sign-up form
		$u_name=$_POST['u_name'];
		$u_pass1=mysqli_real_escape_string($dbc, trim($_POST['u_pass1']));
		$u_pass2=mysqli_real_escape_string($dbc, trim($_POST['u_pass2']));

		if(!empty($u_name) && !empty($u_pass1) && !empty($u_pass2) 
		&& ($u_pass1==$u_pass2)){

			//check uniqueness of username
			$query="SELECT * FROM users WHERE u_name='$u_name'";

			$data=mysqli_query($dbc, $query);
			if(mysqli_num_rows($data)==0){
				//username is unique, append to user talbe
				$query="INSERT INTO users (u_name, u_pass) VALUES ('$u_name', SHA('$u_pass1'))";
				mysqli_query($dbc, $query) or die('Error insertion into user table');

				//get user id
				$query="SELECT u_id FROM users WHERE u_name='$u_name'";
				$row=mysqli_fetch_array(mysqli_query($dbc, $query));
				$u_id=$row['u_id'];
				
				//create user's individual gene bank
				$query="CREATE TABLE genes_of_user_$u_id (g_id int NOT NULL, g_expression text NOT NULL," . 
						" g_update_time timestamp NOT NULL DEFAULT NOW(), UNIQUE (g_id))";
				mysqli_query($dbc, $query) or die('Error create user\'s gene bank');

				//confirm success
				echo '<p>Sign up success!</p>';
				echo '<a href="login.php">click here to login</a>';
			
				mysqli_close($dbc);
				exit();
			}
			//username not unique
			else{
				$error_msg='Account already exists.';
			}
		}
		//data invalid
		else{
			$error_msg='Invalid username or password.';
		}
	}
	mysqli_close($dbc);
?>


<?php
	echo '<p class="error">' . $error_msg . '</p>';
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset>
		<legend>Sign Up</legend>
		<label>Username: </label>
		<input type="text" name="u_name" />
		<label>Password: </label>
		<input type="password" name="u_pass1" />
		<label>Confirm Password: </label>
		<input type="password" name="u_pass2" />
	</fieldset>
	<input type="submit" value="Sign Up" name="submit" />
</form>
