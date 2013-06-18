<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>
     <head>
          <title>Song added to database</title>
     </head>

     <body>
          <?php 
        
          //Connect to database:
          include("connect.php");

          //Include necessary functions:
          include("inserts.php");

          //Inserts row into song table:
          insert_song($mysqli, $_POST["title"], $_POST["year_written"]); 
        

		if(isset($_POST["musician"])) {

	          //Insert rows into musician_song table:
	          insert_musician_song($mysqli, $_POST["musician"], getMaxID($mysqli, "song"));
		}

          ?>  

		<a href="addToDatabase.php">Add to the Database</a>
		<a href="mainPage.html">Return to Main Page</a>
     </body>
</html>

