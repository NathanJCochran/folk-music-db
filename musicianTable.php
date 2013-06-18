<?php

	require_once("mysqliFunctions.php");


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * This internal helper function takes a mysqli stmt object generated
	 *		from one of the functions below and creates the HTML required
	 *		to display the results in a table
      * Param: $stmt:	mysqli stmt object that has been prepared and has
	 *				bound parameters, and which contains all the attributes of musicians
      */
	function _createMusicianTable($stmt) {
		if(!$stmt->bind_result($id, $stage_name, $first_name, $middle_name, $last_name, $born, $died, $hometown, $homestate)) {
          	echo "<p>Bind failed: (" . $stmt->connect_errno . ") " . $stmt->connect_error . "</p>";
          }

		//Create the table header:
		$table= "
			<table border='1'>
				<tr>
		               <th>Stage Name</th>
		               <th>First Name</th>
		               <th>Middle Name</th>
		               <th>Last Name</th>
		               <th>Year Born</th>
		               <th>Year Died</th>
		               <th>Home Town</th>
					<th>Home State</th>
		          </tr>";

		//Create the table rows:
		while($stmt->fetch()) {
                    $table .= "<tr>";
                    $table .= "<td>" . $stage_name . "</td>";
                    $table .= "<td>" . $first_name . "</td>";
                    $table .= "<td>" . $middle_name . "</td>";
                    $table .= "<td>" . $last_name . "</td>";
                    $table .= "<td>" . $born . "</td>";
                    $table .= "<td>" . $died . "</td>";
                    $table .= "<td>" . $hometown . "</td>";
				$table .= "<td>" . $homestate . "</td>";
                    $table .= "</tr>";
               }

		$table.= "</table>";
		$stmt->close();
		return $table;
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Returns a string containing HTML for creating a table of all musicians (no filter)
      * Param:	$mysqli: 	mysqli object for the folk_music database
      */
	function musicianTable($mysqli) {
		$sql="SELECT m.id, m.stage_name, m.first_name, m.middle_name, m.last_name, m.born, m.died, m.hometown, m.homestate
                    FROM musician as m
                    ORDER BY m.first_name;";
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = executeStmt($stmt);
		return _createMusicianTable($stmt);
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Returns a string containing HTML for creating a table of all musicians
	 *		that influenced the specified musician.
      * Param: $mysqli: 		mysqli object for the folk_music database
	 *		$musician_ids:	array of ids of musicians
      */
	function musicianTableFilterInfluenced($mysqli, $musician_ids) {
		$sql="SELECT m.id, m.stage_name, m.first_name, m.middle_name, m.last_name, m.born, m.died, m.hometown, m.homestate
                    FROM musician as m
				INNER JOIN musician_influence as mi ON m.id = mi.influence_id
				WHERE (mi.musician_id = ?) ";
		$sql .= numSqlLines((count($musician_ids)-1), "OR (mi.musician_id = ?)");
		$sql .= "GROUP BY m.id
				ORDER BY m.first_name;";
		$types = numType(count($musician_ids), 'i');
		$stmt = prepareStmt($mysqli, $sql);		
		$stmt = bindNumParams($stmt, $types, $musician_ids);
		$stmt = executeStmt($stmt);
		return _createMusicianTable($stmt);
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Returns a string containing HTML for creating a table of all musicians
	 *		that were influenced by the specified musician.
      * Param: $mysqli: 			mysqli object for the folk_music database
	 *		$influence_ids:	array of ids of musicians
      */
	function musicianTableFilterInfluencedBy($mysqli, $influence_ids) {
		$sql="SELECT m.id, m.stage_name, m.first_name, m.middle_name, m.last_name, m.born, m.died, m.hometown, m.homestate
                    FROM musician as m
				INNER JOIN musician_influence as mi ON m.id = mi.musician_id
				WHERE (mi.influence_id) = ?";
		$sql .= numSqlLines((count($influence_ids)-1), "OR (mi.influence_id = ?)");
		$sql .= "GROUP BY m.id
				ORDER BY m.first_name;";
		$types = numType(count($influence_ids), 'i');
		$stmt = prepareStmt($mysqli, $sql);		
		$stmt = bindNumParams($stmt, $types, $influence_ids);
		$stmt = executeStmt($stmt);
		return _createMusicianTable($stmt);
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Returns a string containing HTML for creating a table of all musicians
	 *		that play the specified instrument
      * Param: $mysqli: 			mysqli object for the folk_music database
	 *		$instrument_ids: 	ids of instruments
      */
	function musicianTableFilterInstrument($mysqli, $instrument_ids) {
		$sql="SELECT m.id, m.stage_name, m.first_name, m.middle_name, m.last_name, m.born, m.died, m.hometown, m.homestate
                    FROM musician as m
				INNER JOIN musician_instrument as mi ON m.id = mi.musician_id
				WHERE (mi.instrument_id = ?)";
		$sql .= numSqlLines((count($instrument_ids)-1), "OR (mi.instrument_id = ?)");
		$sql .= "GROUP BY m.id
				ORDER BY m.first_name;";
		$types = numType(count($instrument_ids), 'i');
		$stmt = prepareStmt($mysqli, $sql);		
		$stmt = bindNumParams($stmt, $types, $instrument_ids);
		$stmt = executeStmt($stmt);
		return _createMusicianTable($stmt);
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Returns a string containing HTML for creating a table of all musicians
	 *		that were involved in the specified album
      * Param: $mysqli: 		mysqli object for the folk_music database
	 *		$album_ids: 	array of ids of albums
      */
	function musicianTableFilterAlbum($mysqli, $album_ids) {
		$sql="SELECT m.id, m.stage_name, m.first_name, m.middle_name, m.last_name, m.born, m.died, m.hometown, m.homestate
                    FROM musician as m
                    INNER JOIN musician_album as ma ON m.id=ma.musician_id
                    WHERE ma.album_id=?";
		$sql .= numSqlLines((count($album_ids)-1), "OR (ma.album_id = ?)");
		$sql .= "GROUP BY m.id
				ORDER BY m.first_name;";
		$types = numType(count($album_ids), 'i');
		$stmt = prepareStmt($mysqli, $sql);		
		$stmt = bindNumParams($stmt, $types, $album_ids);
		$stmt = executeStmt($stmt);
		return _createMusicianTable($stmt);
	}	


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Returns a string containing HTML for creating a table of all musicians
	 *		that have a played a version of the specified song
      * Param: $mysqli:		mysqli object for the folk_music database
	 *		$song_ids:	array of ids of songs
      */
	function musicianTableFilterSong($mysqli, $song_ids) {
          $sql="SELECT m.id, m.stage_name, m.first_name, m.middle_name, m.last_name, m.born, m.died, m.hometown, m.homestate
                    FROM musician as m
                    INNER JOIN musician_song_version as msv ON m.id=msv.musician_id
                    INNER JOIN song_version as sv ON msv.song_version_id = sv.id
                    WHERE sv.song_id = ?";
		$sql .= numSqlLines((count($song_ids)-1), "OR (sv.song_id = ?)");
		$sql .= "GROUP BY m.id
				ORDER BY m.first_name;";
		$types = numType(count($song_ids), 'i');
		$stmt = prepareStmt($mysqli, $sql);		
		$stmt = bindNumParams($stmt, $types, $song_ids);
		$stmt = executeStmt($stmt);
		return _createMusicianTable($stmt);
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Returns a string containing HTML for creating a table of all musicians
	 *		that have a played a version of the specified song on the specified album
      * Param: $mysqli:		mysqli object for the folk_music database
	 *		$album_ids:	array of ids of albums
	 *		$song_ids:	array of ids of songs
      */
	function musicianTableFilterAlbumSong($mysqli, $album_ids, $song_ids) {
		$sql="SELECT m.id, m.stage_name, m.first_name, m.middle_name, m.last_name, m.born, m.died, m.hometown, m.homestate
                    FROM musician as m
                    INNER JOIN musician_song_version as msv ON m.id=msv.musician_id
                    INNER JOIN song_version as sv ON msv.song_version_id = sv.id
                    INNER JOIN musician_album as ma ON sv.album_id = ma.album_id
                    WHERE ((ma.album_id = ?)";
		$sql .= numSqlLines((count($album_ids)-1), "OR (ma.album_id = ?)");
		$sql .= ") AND ((sv.song_id = ?)";
		$sql .= numSqlLines((count($song_ids)-1), "OR(sv.song_id = ?)");
		$sql .= ") GROUP BY m.id
                    ORDER BY m.first_name;";
		$types = numType(count($album_ids), 'i') . numType(count($song_ids), 'i');
		$stmt = prepareStmt($mysqli, $sql);		
		$stmt = bindNumParams($stmt, $types, array_merge($album_ids, $song_ids));
		$stmt = executeStmt($stmt);
		return _createMusicianTable($stmt);
	}


?>
