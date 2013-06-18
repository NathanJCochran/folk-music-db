<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>
	<head>
		<title>Musician added to database</title>
	</head>

	<body>
		<?php 
		
		//Connect to database:
		require_once("connect.php");
	
          //Include necessary functions:
          require_once("inserts.php");

		//Inserts row into musician table:
		insert_musician($mysqli, $_POST["stage_name"], $_POST["first_name"], $_POST["middle_name"], $_POST["last_name"], $_POST["born"], $_POST["died"], $_POST["hometown"], $_POST["homestate"]);
	

		//Inserts rows into musician_instrument table:
		if(isset ($_POST["instrument"])) {
			insert_musician_instrument($mysqli, getMaxID($mysqli, "musician"), $_POST["instrument"]);
		}
		
		?>

		<a href="addToDatabase.php">Add to the Database</a>
		<a href="mainPage.html">Return to Main Page</a>
	</body>
</html>
