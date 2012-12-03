<?php

	//Start the session
	require_once('startsession.php');

	//Insert header
	$page_title='Evolve';
	require_once('header.php');


	//Show the navigation menu
	require_once('navmenu.php');
?>

<?php
	
	//prepare erorr message var
	$error_msg="";
	//if not even logged in
	if (!isset($_SESSION['u_id'])){
		$error_msg= 'Sorry Please login to evolve a gene.';
	}

	//if logged in and already posted
	else if (isset($_GET['submit'])){
		//Connect to database
		require_once('connectvars.php');
		$dbc=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to MySQL Database');
		
		//parse geneinfo
		$u_id=$_SESSION['u_id'];
		$g_name=mysqli_real_escape_string($dbc, trim($_GET['g_name']));
		$g_expression=mysqli_real_escape_string($dbc, trim($_GET['g_expression']));

		
		if(!empty($g_name) && !empty($g_expression)){
				
			//fix genes
			$query="select * FROM genes WHERE g_name='$g_name'";
			$data=mysqli_query($dbc, $query);
			$checkgene=mysqli_num_rows($data);
			if ($checkgene == 0) {  //fresh gene

				//insert into gene bank
				$query="INSERT INTO genes (g_name) VALUES ('$g_name')";
				mysqli_query($dbc, $query);
			}else{

				//update g_update_time
				$row=mysqli_fetch_array($data);
				$g_id=$row['g_id'];
				$query="UPDATE genes SET g_update_time=NOW() WHERE g_id='$g_id'";
				mysqli_query($dbc, $query) or die ('Error update genes');
			}


			//get g_id from genes
			$query="SELECT * FROM genes WHERE g_name='$g_name'";
			$data=mysqli_query($dbc, $query);
			$row=mysqli_fetch_array($data);
			$g_id=$row['g_id'];
				

			//fix users_of_gene
			if ($checkgene == 0) {  //create table when no current table exists
				$query="CREATE TABLE users_of_gene_$g_id (u_id int NOT NULL UNIQUE, g_update_time timestamp NOT NULL DEFAULT NOW())";
				mysqli_query($dbc, $query) or die('Error create gene\'s own table');
			}

			$query="SELECT * FROM users_of_gene_$g_id WHERE u_id='$u_id'";
			$data=mysqli_query($dbc, $query) or die('Error retrieving gene\'s own table');
			$checkuser=mysqli_num_rows($data);
			if ($checkuser == 0) { //new owner of the gene
				$query="INSERT INTO users_of_gene_$g_id (u_id) VALUES ('$u_id')";
				mysqli_query($dbc, $query) or die('Error insert into gene\'s own talbe');
			}else{ //old user update gene
				$query="UPDATE users_of_gene_$g_id SET g_update_time=NOW() WHERE u_id='$u_id'";
				mysqli_query($dbc, $query) or die('Error update gene time in gene\'s own table');
			}

			//fix genes_of_user
			$query="SELECT * FROM genes_of_user_$u_id WHERE g_id='$g_id'";
			$data=mysqli_query($dbc, $query);
			$checkgene=mysqli_num_rows($data);
			if ($checkgene == 0) { //insert fresh gene
				$msg='Fresh gene acquired.';
				$query="INSERT INTO genes_of_user_$u_id (g_id, g_expression) VALUES ('$g_id', '$g_expression')";
				mysqli_query($dbc, $query) or die('Error insert into user\'s own table');
			}else{ //update old gene
				$msg='Existing gene mutated.';
				$query="UPDATE genes_of_user_$u_id SET g_expression='$g_expression', g_update_time=NOW() WHERE g_id='$g_id'";
				mysqli_query($dbc, $query) or die('Error update into user\'s own table');
			}

			//redirect to my gene
			$mygene_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/mygene.php';
			header('Location: ' . $mygene_url . '?msg=' .$msg );
	
		}else{
			$error_msg= 'Sorry, please fill in both name and expression before evolving.';
		} 
	}

	//print error
	echo '<p class="error">' . $error_msg . '</p>';
	
?>

<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset>
		<legend>Evolve</legend>
		<label for="g_name">Gene Name:</label>
		<input type="text" name="g_name" value="<?php if (!empty($_GET['g_name'])) echo $_GET['g_name']; ?>" />
		<label for="g_expression">Expression:</label>
		<input type="text" name="g_expression" />
	</fieldset>
	<input type="submit" value="Add" name="submit" />
</form>

<?php
	//insert footer
	require_once('footer.php');
?>
