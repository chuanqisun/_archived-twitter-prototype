<?php

	//Start the session
	require_once('startsession.php');

	//Insert header
	$page_title='New Gene';
	require_once('header.php');


	//Show the navigation menu
	require_once('navmenu.php');
?>

<?php
	
	//prepare erorr message var
	$error_msg="";
	//if not even logged in
	if (!isset($_SESSION['id'])){
		$error_msg= 'Sorry Please login to add new gene.';
	}

	//if logged in and already posted
	else if (isset($_POST['submit'])){
		//Connect to database
		require_once('connectvars.php');
		$dbc=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting to MySQL Database');
		
		//parse geneinfo
		$id=$_SESSION['id'];
		$genename=$_POST['genename'];
		$geneexpression=$_POST['geneexpression'];
		
		if(!empty($genename) && !empty($geneexpression)){
				
			//fix genes bank
			$query="select * FROM genes WHERE name='$genename'";
			$data=mysqli_query($dbc, $query);
			$checkgene=mysqli_num_rows($data);
			if ($checkgene == 0) {  //fresh gene

				//insert into gene bank
				$query="INSERT INTO genes (name, name_count, expression_count) VALUES ('$genename', '1', '1')";
				mysqli_query($dbc, $query);
			}else{  //gene already exists
			echo "old gene in genes bank";	
			
				//increment in gene bank
			}


			//get gene id from gene bank
			$query="SELECT * FROM genes WHERE name='$genename'";
			$data=mysqli_query($dbc, $query);
			$row=mysqli_fetch_array($data);
			$gene_id=$row['id'];
				

			//fix gene's own table
			if ($checkgene == 0) {  //create table when no current table exists
				$query="CREATE TABLE gene_$gene_id (user_id int NOT NULL, expression text NOT NULL)";
				mysqli_query($dbc, $query) or die('Error create gene\'s own table');
			}

			$query="INSERT INTO gene_$gene_id (user_id, expression) VALUES ('$id', '$geneexpression')";
			mysqli_query($dbc, $query) or die('Error insert into gene\'s own talbe');

			//fix user's individual gene bank
			$query="SELECT * FROM user_$id WHERE name='$genename'";
			$data=mysqli_query($dbc, $query);
			$checkgene=mysqli_num_rows($data);
			if ($checkgene == 0) { //fresh gene
				//insert into user's own bank
				$query="INSERT INTO user_$id (id, name, expression) VALUES ('$gene_id', '$genename', '$geneexpression')";
				mysqli_query($dbc, $query);
			}else{ //old gene
				//update user's individual gene bank
				echo "old gene in user's gene bank";
			}

			
			
		}else{
			$error_msg= 'Sorry Please fill in both name and expression before adding';
		} 
	}

	//print error
	echo '<p class="error">' . $error_msg . '</p>';
	
?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset>
		<legend>New Gene</legend>
		<label for="genename">Gene Name:</label>
		<input type="text" name="genename" />
		<label for="expression">Gene Expression:</label>
		<input type="text" name="geneexpression" />
	</fieldset>
	<input type="submit" value="Add Gene" name="submit" />
</form>

<?php
	//insert footer
	require_once('footer.php');
?>
