<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>
     <head>
          <title>Album added to database</title>
     </head>

     <body>
          <?php 
          
          //Connect to database:
          require_once("connect.php");

		//Include necessary functions:
		require_once("inserts.php");

		//Inserts row into album table:
      	insert_album($mysqli, $_POST["title"], $_POST["year"], $_POST["record_label"]); 
		
		//Insert rows into musician_album table:
		insert_musician_album($mysqli, $_POST["musician"], getMaxID($mysqli, "album"))

		
		//Determine # of songs via POST variable:

		


		//Create numbered forms for each song:

		//Create options: check for original, select for version_of

		//If version_of, create new song entry
		

		

		

		?>

		<a href="addToDatabase.php">Add to the Database</a>
		<a href="mainPage.html">Return to Main Page</a>
	</body>
</html>
