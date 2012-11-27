<?php 
	
	require_once('connectvars.php');
	
	//connect to database
	$dbc=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to MySQL server.');

	{		
		//extract profile from sign-up form
		$username=$_POST['username'];
		$password1=$_POST['password1'];
		$password2=$_POST['password2'];


		if(!empty($username) && !empty($password1) && !empty($password2) 
		&& ($password1==$password2)){

			//check uniqueness of username
			$query="SELECT * FROM users WHERE username='$username'";

			$data=mysqli_query($dbc, $query);
			if(mysqli_num_rows($data)==0){
				//username is unique, append to user talbe
				$query="INSERT INTO users (username, password, gene_count) VALUES ('$username', SHA('$password1'), '0')";
				mysqli_query($dbc, $query) or die('Error insertion into user table');

				//get user id
				$query="SELECT id FROM users WHERE username='$username'";
				$row=mysqli_fetch_array(mysqli_query($dbc, $query));
				$id=$row['id'];
				
				//create user's individual gene bank
				$query="CREATE TABLE user_$id (gene_id int NOT NULL UNIQUE, name varchar(255) NOT NULL UNIQUE, expression text NOT NULL, PRIMARY KEY (id))";
				mysqli_query($dbc, $query) or die('Error create user\'s gene bank');

				//confirm success
				echo '<p>Sign up success!</p>';
				echo '<a href="login.html">click here to login</a>';
			
				mysqli_close($dbc);
				exit();
			}
			//username not unique
			else{
				echo '<p>Account already exists</p>';
			}
		}
		//data invalid
		else{
			echo '<p>Invalid username or password</p>';
		}
	}
	mysqli_close($dbc);
?>
