<?php

	require_once("mysqliFunctions.php");

     /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * This internal helper function takes a mysqli stmt object generated
      *        from one of the above functions and creates the HTML required
      *        to display the results in a table
      * Param: $stmt:    mysqli stmt object that has been prepared and has
      *                  bound parameters, and which contains all the attributes of songs
      */
	function _createSongTable($stmt) {

          if(!$stmt->bind_result($id, $title, $year_written)) {
               echo "<p>Bind failed: (" . $stmt->connect_errno . ") " . $stmt->connect_error . "</p>";
          }

		//Create the table header:
          $table= "
               <table border='1'>
                    <tr>
                         <th>Song Title</th>
                         <th>Year Written</th>
                    </tr>";

		//Create the table rows:
          while($stmt->fetch()) {
                    $table .= "<tr>";
                    $table .= "<td>". $title ."</td>";
                    $table .= "<td>". $year_written ."</td>";
                    $table .= "</tr>";
               }

          $table.= "</table>";
		$stmt->close();
          return $table;
	}


	 /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Returns a string containing HTML for creating a table of all songs (no filter)
      * Param: $mysqli:  mysqli object for the folk_music database
      */
	function songTable($mysqli) {
		$sql="SELECT s.id, s.title, s.year_written 
				FROM song as s
				ORDER BY s.title;";
		$stmt = prepareStmt($mysqli, $sql);	
		$stmt = executeStmt($stmt);
		return _createSongTable($stmt);

	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Returns a string containing HTML for creating a table of all songs 
	 *		of which a version was played by the specified musician
      * Param: $mysqli:  	mysqli object for the folk_music database
	 *		$musician_ids:	array of ids of musicians
      */
	function songTableFilterMusician($mysqli, $musician_ids) {
		$sql="SELECT s.id, s.title, s.year_written
				FROM song as s
				INNER JOIN song_version as sv ON s.id = sv.song_id
				INNER JOIN musician_song_version as msv ON sv.id = msv.song_version_id
				WHERE (msv.musician_id = ?)";
		$sql .= numSqlLines((count($musician_ids)-1), "OR (msv.musician_id = ?)");
		$sql .= "GROUP BY s.id
				ORDER BY s.title;";
		$types = numType(count($musician_ids), 'i');
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = bindNumParams($stmt, $types, $musician_ids);
		$stmt = executeStmt($stmt);
		return _createSongTable($stmt);

	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Returns a string containing HTML for creating a table of all songs 
	 *		of which a version appeared on the specified album
      * Param: $mysqli:  	mysqli object for the folk_music database
	 *		$album_ids:	array of ids of albums
      */
	function songTableFilterAlbum($mysqli, $album_ids) {
		$sql="SELECT s.id, s.title, s.year_written
				FROM song as s
				INNER JOIN song_version as sv ON s.id = sv.song_id
				WHERE (sv.album_id = ?)";
		$sql .= numSqlLines((count($album_ids)-1), "OR (sv.album_id = ?)");
		$sql .= "GROUP BY s.id
				ORDER BY s.title;";
		$types = numType(count($album_ids), 'i');
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = bindNumParams($stmt, $types, $album_ids);
		$stmt = executeStmt($stmt);
	     return _createSongTable($stmt);
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Returns a string containing HTML for creating a table of all songs 
	 *		of which a version was played by the specified musician on the specified album
      * Param: $mysqli:  	mysqli object for the folk_music database
	 *		$musician_ids:	array of ids of musicians
	 *		$album_ids:	array of ids of albums
      */
	function songTableFilterMusicianAlbum($mysqli, $musician_ids, $album_ids) {
		$sql="SELECT s.id, s.title, s.year_written
				FROM song as s
				INNER JOIN song_version as sv ON s.id = sv.song_id
				INNER JOIN musician_song_version as msv ON sv.id = msv.song_version_id
				WHERE ((msv.musician_id = ?)";
		$sql .= numSqlLines((count($musician_ids)-1), "OR (msv.musician_id = ?)");
		$sql .=	") AND ((sv.album_id = ?)";
		$sql .= numSqlLines((count($album_ids)-1), "OR (sv.album_id = ?)");
		$sql .= "GROUP BY s.id
				ORDER BY s.title;";
		$types = numType(count($musician_ids), 'i') . numType(count($album_ids), 'i');
		$stmt = prepareStmt($stmt, $sql);
		$stmt = bindNumParams($stmt, $types, array_merge($musician_ids, $album_ids));
		$stmt = executeStmt($stmt);
    	     return _createSongTable($stmt);
	}

?>
