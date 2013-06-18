<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>
     <head>
          <title>Song Version added to database</title>
     </head>

     <body>
          <?php 
    
          //Connect to database:
          require_once("connect.php");

          //Include necessary functions:
          require_once("inserts.php");

          //Inserts row into song table:
          insert_song_version($mysqli, $_POST["song"], $_POST["album"], $_POST["name"], $_POST["lyrics"]); 
    
          //Insert rows into musician_song table:
          insert_musician_song_version($mysqli, $_POST["musician"], getMaxID($mysqli, "song_version"));

          ?>  
		
		<a href="addToDatabase.php">Add to the Database</a>
		<a href="mainPage.html">Return to Main Page</a>
     </body>
</html>

