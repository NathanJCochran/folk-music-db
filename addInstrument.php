<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>
     <head>
          <title>Instrument added to database</title>
     </head>

     <body>
          <?php 
        
          //Connect to database:
          require_once("connect.php");

          //Include necessary functions:
          require_once("inserts.php");

          //Inserts row into song table:
          insert_instrument($mysqli, $_POST["type"]); 
              
          ?>  

		<a href="addToDatabase.php">Add to the Database</a>
		<a href="mainPage.html">Return to Main Page</a>
     </body>
</html>
