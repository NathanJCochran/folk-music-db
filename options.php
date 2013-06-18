<?php

	require_once("mysqliFunctions.php");

	function containsVals($array) {
		if((count($array) > 1) or (!in_array('0', $array))) {
			return true;
		}
		return false;
	}

	function getPost($postKey) {
		if(isset($_POST[$postKey])) {
			return $_POST[$postKey];
		}
		return array('0');
	}
	
	function getGet($getKey) {
		if(isset($_GET[$getKey])) {
			return $_GET[$getKey];
		}
		return array('0');
	}

	function setSelected($val, $selectedVals) {
		if(in_array($val, $selectedVals)) {
			return " selected='selected' ";
		}
		return "";
	}

	function _createOptions($stmt, $selectedVals) {
		$optionStr="";
          if(!$stmt->bind_result($id, $optionText)) {
               echo "<p>Bind failed: (" . $stmt->connect_errno . ") " . $stmt->connect_error . "</p>";
          }
          while($stmt->fetch()) {
	          $optionStr .= "<option value= '" . $id ."'" . setSelected($id, $selectedVals) . ">" . $optionText . "</option>\n";
          }
          $stmt->close();
		return $optionStr;
	}


	function _createAlbumOptions($stmt, $selectedVals) {
		$optionStr = "";
          if(!$stmt->bind_result($album_id, $title, $musician_id, $stage_name)) {
               echo "<p>Bind failed: (" . $stmt->connect_errno . ") " . $stmt->connect_error . "</p>";
          }
          
          //Outer loop: fetches each album_id and creates an option for that album:
 		if($stmt->fetch()) {
			$badFetch=false;
		}
		else{
	          $badFetch=true;
		}
          while(!$badFetch) {
               $optionStr .= "<option value= '".$album_id."'" . setSelected($album_id, $selectedVals) . ">" . $title . " by: ";

			//Inner loop: Adds each musician's name to the album option tag:
			do{
              		$optionStr .= $stage_name . ", ";
                    $last = $album_id;
                    if(!$stmt->fetch()) {
                         $badFetch=true;
                    }
               } while(!$badFetch and ($last==$album_id));
               $optionStr .= "</option>\n";
          }

		$stmt->close();
		return $optionStr;
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Creates the HTML option tags for a select box containing all states
      * Param: $mysqli:       mysqli object for the folk_music database
      *        $defaultOpts:	array of ids of the default states (the states currently selected)
      */
	function stateOptions($mysqli, $defaultOpts) {
		$sql="SELECT id, abbrev
				FROM state
				ORDER BY abbrev;";
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = executeStmt($stmt);		
		return _createOptions($stmt, $defaultOpts);
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Creates the HTML option tags for a select box containing all musicians (no filter)
      * Param: $mysqli:       mysqli object for the folk_music database
      *        $defaultOpts:	array of ids of the default musicians (the musicians currently selected)
      */
	function musicianOptions($mysqli, $defaultOpts) {	
		$sql="SELECT id, stage_name
				FROM musician
				ORDER BY first_name;";
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = executeStmt($stmt);		
		return _createOptions($stmt, $defaultOpts);
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Creates the HTML option tags for a select box containing all musicians
	 *		on the specified album
      * Param: $mysqli:       mysqli object for the folk_music database
      *        $defaultOpts:	array of ids of the default musicians (the musicians currently selected)
	 *		$album_id:	array of ids of albums
      */
	function musicianOptionsForAlbum($mysqli, $defaultOpts, $album_ids) {
		$sql="SELECT id, stage_name
				FROM musician as m
				INNER JOIN musician_album as ma ON m.id = ma.musician_id
				WHERE (ma.album_id = ?)";
		$sql .= numSqlLines((count($album_ids)-1), "OR (ma.album_id = ?)");
		$sql .= "GROUP BY m.id
				ORDER BY m.stage_name;";
		$types = numType(count($album_ids), 'i');
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = bindNumParams($stmt, $types, $album_ids);
		$stmt = executeStmt($stmt);		
		return _createOptions($stmt, $defaultOpts);
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Creates the HTML option tags for a select box containing all instruments
      * Param: $mysqli:       mysqli object for the folk_music database
      *        $defaultOpts:	array of ids of the default instruments (the instruments currently selected)
      */
	function instrumentOptions($mysqli, $defaultOpts) {
		$sql="SELECT id, type
				FROM instrument
				ORDER BY type;";
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = executeStmt($stmt);		
		return _createOptions($stmt, $defaultOpts);
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Creates the HTML option tags for a select box containing all songs (no filter)
      * Param: $mysqli:       mysqli object for the folk_music database
      *        $defaultOpts:	array of ids of the default songs (the songs currently selected)
      */
	function songOptions($mysqli, $defaultOpts) {
		$sql="SELECT id, title
				FROM song
				ORDER BY title;";
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = executeStmt($stmt);		
		return _createOptions($stmt, $defaultOpts);
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Creates the HTML option tags for a select box containing all songs
	 *		of which a version is on the specified album
      * Param: $mysqli:       mysqli object for the folk_music database
      *        $defaultOpts:	array of ids of the default songs (the songs currently selected)
	 *		$album_ids:	array of ids of albums
      */
	function songOptionsForAlbum($mysqli, $defaultOpts, $album_ids) {
		$sql="SELECT s.id, s.title
				FROM song as s
				INNER JOIN song_version as sv ON s.id = sv.song_id
				WHERE (sv.album_id = ?)";
		$sql .= numSqlLines((count($album_ids)-1), "OR (sv.album_id = ?)");
		$sql .= "GROUP BY s.id
				ORDER BY title;";
		$types = numType(count($album_ids), 'i');
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = bindNumParams($stmt, $types, $album_ids);
		$stmt = executeStmt($stmt);		
		return _createOptions($stmt, $defaultOpts);
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Creates the HTML option tags for a select box containing all record_labels
      * Param: $mysqli:       mysqli object for the folk_music database
      *        $defaultOpts:	array of ids of the default record_labels (the record_labels currently selected)
      */
	function recordLabelOptions($mysqli, $defaultOpts) {
		$sql="SELECT id, name
				FROM record_label
				ORDER BY name;";
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = executeStmt($stmt);		
		return _createOptions($stmt, $defaultOpts);
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Creates the HTML option tags for a select box containing all albums (no filter)
      * Param: $mysqli:       mysqli object for the folk_music database
      *        $defaultOpts:	array of ids of the default albums (the albums currently selected)
      */
	function albumOptions ($mysqli, $defaultOpts) {
		$sql="SELECT a.id, a.title, ma.musician_id, m.stage_name
				FROM album as a
				INNER JOIN musician_album as ma ON a.id = ma.album_id
				INNER JOIN musician as m ON ma.musician_id = m.id
				ORDER BY a.title;";
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = executeStmt($stmt);		
		return _createAlbumOptions($stmt, $defaultOpts);
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Creates the HTML option tags for a select box containing all albums
	 *		which contain a version of the specified song
      * Param: $mysqli:       mysqli object for the folk_music database
      *        $defaultOpts:	array of ids of the default albums (the albums currently selected)
	 *		$song_ids:	array of ids of songs
      */
	function albumOptionsForSong ($mysqli, $defaultOpts, $song_ids) {
		$sql="SELECT a.id, a.title, m.id, m.stage_name
				FROM album as a
				INNER JOIN song_version as sv ON a.id = sv.album_id
				INNER JOIN musician_album as ma ON a.id = ma.album_id
				INNER JOIN musician as m ON ma.musician_id = m.id
				WHERE (sv.song_id = ?)";
		$sql .= numSqlLines((count($song_ids)-1), "OR (sv.song_id = ?)");
		$sql .= "GROUP BY a.id
				ORDER BY a.title;";
		$types = numType(count($song_ids), 'i');
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = bindNumParams($stmt, $types, $song_ids);
		$stmt = executeStmt($stmt);		
		return _createAlbumOptions($stmt, $defaultOpts);
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Creates the HTML option tags for a select box containing all albums
	 *		which the specified musicians were involved in
      * Param: $mysqli:       mysqli object for the folk_music database
      *        $defaultOpts:	array of ids of the default albums (the albums currently selected)
	 *		$musician_ids:	array of ids of musicians
      */
	function albumOptionsForMusician($mysqli, $defaultOpts, $musician_ids) {
		$sql="SELECT a.id, a.title, m.id, m.stage_name
				FROM (
					SELECT a.id, a.title
					FROM album as a
					INNER JOIN musician_album as ma ON a.id = ma.album_id
					WHERE (ma.musician_id = ?)";
		$sql .= numSqlLines((count($musician_ids)-1), "OR (ma.musician_id = ?)");
		$sql .= ") as a
				INNER JOIN musician_album as ma ON a.id = ma.album_id
				INNER JOIN musician as m ON ma.musician_id = m.id
				ORDER BY a.title;";
		$types = numType((count($musician_ids)-1), 'i');
		$stmt = prepareStmt($mysqli, $sql);
		$stmt = bindNumParams($stmt, $types, $musician_ids);
		$stmt = executeStmt($stmt);		
		return _createAlbumOptions($stmt, $defaultOpts);
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *  
      * Creates HTML tags for checkboxes corresponding to the various musicians
	 *		involved in a given album
      * Param: $mysqli:       mysqli object for the folk_music database
	 *		$album_id:	id of album
      */
	function musicianCheckboxes($mysqli, $album_id) {
		$checkboxStr = "";

		if($album_id != 0) {
			$sql = "SELECT m.id, m.stage_name
					FROM musician as m
					INNER JOIN musician_album as ma ON m.id = ma.musician_id
					WHERE ma.album_id = ?
					ORDER BY m.stage_name;";
			$stmt = prepareStmt($mysqli, $sql);
	
			if(!($stmt->bind_param("i", $album_id))) {
	               echo "<p>Bind failed: (" . $stmt->errno . ") " . $stmt->error . "</p>";
	          }
	
			$stmt = executeStmt($stmt);		
	
	          if(!$stmt->bind_result($musician_id, $stage_name)) {
	               echo "<p>Bind failed: (" . $stmt->connect_errno . ") " . $stmt->connect_error . "</p>";
	          }
	    
	     	$checkboxStr .= "<label for='musician'>Musician(s):</label>\n";
			while($stmt->fetch()) {
	          	$checkboxStr .= "<input type='checkbox' name='musician[]' checked='checked' value=" . $musician_id . "/>\n";
	          	$checkboxStr .= "<label style='clear:none; width:auto;' for='musician[]'> - " . $stage_name . "</label>\n";
			}
			return $checkboxStr;
		}
	}
?>
