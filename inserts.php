<?php

	require_once("mysqliFunctions.php");


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
	 * Inserts a single row into the instrument table.
	 * Param: $mysqli: mysqli object for the folk_music database
	 *		$type: the name of the type of instrument
	 */
	function insert_instrument($mysqli, $type) {
		$sql="INSERT INTO instrument (type)
				VALUES (?);";
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = bindNumParams($stmt, 's', array($type));
		$stmt = executeStmt($stmt);
		$stmt->close();
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
	 * Inserts a single row into the musician table.
	 * Param: $mysqli: mysqli object for the folk_music database
	 *		$stage_name: musician's full stage name
	 * 		$first_name: musician's first name
	 *		$middle_name: musician's middle name
	 * 		$last_name: musician's last name
	 *		$born: year born
	 *		$died: year died
	 *		$hometown: musician's home town
	 *		$homestate: musician's home state (foreign key for state(id))
	 */
	function insert_musician($mysqli, $stage_name, $first_name, $middle_name, $last_name, $born, $died, $hometown, $homestate) {
		$sql="INSERT INTO musician (stage_name, first_name, middle_name, last_name, born, died, hometown, homestate)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = bindNumParams($stmt, "ssssiisi", array($stage_name, $first_name, $middle_name, $last_name, $born, $died, $hometown, $homestate));
		$stmt = executeStmt($stmt);
		$stmt->close();
	}

	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
	 * Inserts multiple rows into the musician_instrument table, specifying
	 *		which instruments a given musician played
	 * Param: $mysqli: mysqli object for the folk_music database
	 *		$musician_id: id for the musician in question
	 * 		$song_id: array of ids for the instruments played
	 */
	function insert_musician_instrument($mysqli, $musician_id, $instrument_ids) {
		$sql="INSERT INTO musician_instrument (musician_id, instrument_id)
				VALUES (?, ?)";
		$sql .= numSqlLines((count($instrument_ids)-1), ", (?, ?)");
		$sql .= ";";
		$types = numType(count($instrument_ids), "ii");
		$params = featherIn($musician_id, $instrument_ids);
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = bindNumParams($stmt, $types, $params);
		$stmt = executeStmt($stmt);
		$stmt->close();
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
	 * Inserts a single row into the record_label table
	 * Param: $mysqli: mysqli object for the folk_music database
	 *		$name: name of the record label
	 * 		$year_founded: year the record label was founded
	 */
	function insert_record_label($mysqli, $name, $year_founded) {
		$sql="INSERT INTO record_label (name, year_founded)
				VALUES (?, ?);";
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = bindNumParams($stmt, "si", array($name, $year_founded));
		$stmt = executeStmt($stmt);
		$stmt->close();
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
	 * Inserts a single row into the album table.
	 * Param: $mysqli: mysqli object for the folk_music database
	 *		$title: title of the album
	 *		$year: year album was released
	 *		$record_label: record label that produced the album
	 */
	function insert_album($mysqli, $title, $year, $record_label) {
		$sql = "INSERT INTO album (title, year, record_label)
				VALUES (?, ?, ?);";
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = bindNumParams($stmt, "sii", array($title, $year, $record_label));          
		$stmt = executeStmt($stmt);          
		$stmt->close();
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
	 * Inserts multiple rows into the musician_album table, specifying
	 *		which musicians played on the specified album.
	 * Param: $mysqli: mysqli object for the folk_music database
	 *		$musician_ids: array of ids for musicians who played on the album
	 * 		$album_id: id for the album in question
	 */
	function insert_musician_album($mysqli, $musician_ids, $album_id) {
		$sql="INSERT INTO musician_album (album_id, musician_id)
				VALUES (?, ?)";
		$sql .= numSqlLines((count($musician_ids)-1), ", (?, ?)");
		$sql .= ";";
		$types = numType(count($musician_ids), "ii");
		$params = featherIn($album_id, $musician_ids);
		$stmt = prepareStmt($mysqli, $sql);
		$stmt - bindNumParams($stmt, $types, $params);
		$stmt = executeStmt($stmt);
		$stmt->close();
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
	 * Inserts a single row into the song table.
	 * Param: $mysqli: mysqli object for the folk_music database
	 *		$title: title of the song
	 * 		$year_written: year the song was written
	 */
	function insert_song($mysqli, $title, $year_written) {
		$sql="INSERT INTO song (title, year_written)
				VALUES (?, ?);";
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = bindNumParams($stmt, "si", array($title, $year_written));
		$stmt = executeStmt($stmt);
		$stmt->close();
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
	 * Inserts multiple rows into the musician_song table, specifying
	 *		which musicians wrote which songs.
	 * Param: $mysqli: mysqli object for the folk_music database
	 *		$musician_ids: array of ids for musicians who played on the album
	 * 		$song_id: id for the song in question
	 */
	function insert_musician_song($mysqli, $musician_ids, $song_id) {
		$sql="INSERT INTO musician_song (song_id, musician_id)
				VALUES (?, ?)";
		$sql .= numSqlLines((count($musician_ids)-1), ", (?, ?)");
		$sql .= ";";
		$types = numType(count($musician_ids), "ii");
		$params = featherIn($song_id, $musician_ids);
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = bindNumParams($stmt, $types, $params);
		$stmt = executeStmt($stmt);
		$stmt->close();
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
	 * Inserts a single row into the song_version table.
	 * Param: $mysqli: mysqli object for the folk_music database
	 *		$song: title of the song
	 * 		$year_written: year the song was written
	 */
	function insert_song_version($mysqli, $song_id, $album_id, $name, $lyrics) {
		$sql="INSERT INTO song_version (song_id, album_id, name, lyrics)
				VALUES (?, ?, ?, ?);";
		$stmt = prepareStmt($mysqli, $sql);
          $stmt = bindNumParams($stmt, "iiss", array($song_id, $album_id, $name, $lyrics));
		$stmt = executeStmt($stmt);
		$stmt->close();
	}

	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
	 * Inserts multiple rows into the musician_song_version table, specifying
	 *		which musicians played in a given song version
	 * Param: $mysqli: mysqli object for the folk_music database
	 *		$musician_ids: Array of ids for musicians who played in the song_version
	 * 		$song_version_id: id of the song_version in question
	 */
	function insert_musician_song_version($mysqli, $musician_ids, $song_version_id) {
		$sql="INSERT INTO musician_song_version (song_version_id, musician_id)
				VALUES (?, ?)";
		$sql .= numSqlLines((count($musician_ids)-1), ", (?, ?)";
		$sql .= ";";
		$types = numType(count($musician_ids), "ii");
		$params = featherIn($song_version_id, $musician_ids);
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = bindNumParams($stmt, $types, $params);
		$stmt = executeStmt($stmt);
		$stmt->close();
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
	 * Inserts multiple rows into the musician_influence table, specifying
	 *		which musicians influenced a given musician.
	 * Param: $mysqli: mysqli object for the folk_music database
	 *		$musician_id: id of the musician in question
	 * 		$influence_ids: array of ids for musicians who influenced the given musician
	 */
	function insert_musician_influence($mysqli, $musician_id, $influence_ids) {
		$sql="INSERT INTO musician_influence (musician_id, influence_id)
				VALUES (?, ?)";
		$sql .= numSqlLines((count($influence_ids)-1), ", (?, ?)");
		$sql .= ";";
		$types = numType(count($influence_ids), "ii");
		$params = featherIn($musician_ids, $influence_ids);
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = bindNumParams($stmt, $types, $params);
		$stmt = executeStmt($stmt);
		$stmt->close();
	}


     /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Returns the maximum ID from the specified table.  Useful for getting
      *        the ID of the last row added to a table.
      * Param: $mysqli: mysqli object for the folk_music database
      *        $table: name of the table from which to get the max id
      */
     function getMaxID($mysqli, $table) {
		$sql="SELECT MAX(id) FROM " . $table . " ;";
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = executeStmt($stmt);

          if(!$stmt->bind_result($max_id)) {
               echo "<p>Bind failed: (" . $stmt->connect_errno . ") " . $stmt->connect_error . "</p>";
          }
 
          $stmt->fetch();
          $stmt->close();

          return $max_id;
     }   

?>

