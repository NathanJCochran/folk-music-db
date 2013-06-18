<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>
     <head>
          <title>Influence added to database</title>
     </head>

     <body>
          <?php 
    
          //Connect to database:
          require_once("connect.php");

          //Include necessary functions:
          require_once("inserts.php");

          //Insert rows into musician_influence table:
          insert_musician_influence($mysqli, $_POST["musician"], $_POST["influence"]);

          ?>  

		<a href="addToDatabase.php">Add to the Database</a>
		<a href="mainPage.html">Return to Main Page</a>
     </body>
</html>
