<?php

require_once("options.php");

function displayMusicianForm($mysqli) {
	?>

	     <form action="addMusician.php" method="post">
               <fieldset>
                    <legend>Musician</legend>
    
                    <label for="stage_name">Stage Name:</label>
                         <input type="text" name="stage_name"/>
    
                    <label for="first_name">First Name:</label>
                    <input type="text" name="first_name"/>
    
                    <label for="middle_name">Middle Name:</label>
                         <input type="text" name="middle_name"/>
    
                    <label for="last_name">Last Name:</label>
                         <input type="text" name="last_name"/>

                    <label for="born">Year Born:</label>
                    <input type="text" name="born"/>
    
                    <label for="died">Year Died:</label>
                    <input type="text" name="died"/>
    
                    <label for="hometown">Home Town:</label>
                    <input type="text" name="hometown"/>

                    <label for="homestate">Home State:</label>
				<select name="homestate" size="1">
					<?php
						echo stateOptions($mysqli, array());
					?>
				</select>

                    <label for="instrument">Instrument(s):</label>
                    <select name="instrument[]" size=<?PHP echo LIST_SIZE?> multiple="multiple">
                         <?php
                              echo instrumentOptions($mysqli, array());
                         ?>  
                    </select>
    
                    <label for="submit"></label>
                    <input type="submit" value="Add Musician"/>
               </fieldset>
          </form>
	<?php
}


function displaySongVersionForm($mysqli) {

     //Creates wrapper variables for posted variables, setting value to 0 if not posted: 
	$defaultAlbum = getGet("albumChosem");
	$defaultSong = getGet("songChosen");

	?>

		<!-- The following javascript is run when the user selects an album 
               from the album drop-down menu in the song_version box.  It copies 
               the info entered so far in to the following hidden form, then 
               auto-submits the hidden form, returning back to this page --> 
          <script type="text/javascript"> 
               function setAlbum(form) { 
                    document.getElementById("albumChosen").value = form.elements["album"].value; 
                    document.getElementById("songChosen").value = form.elements["song"].value; 
                    document.getElementById("scrollPosition").value = window.pageYOffset; 
                    document.getElementById("updateAlbum").submit(); 
               } 
          </script> 
      
      
          <!-- Hidden form.  Used to submit data back to this page, allowing for 
               the dynamic creation of checkbox fields in the addSongVersion form --> 
          <form method="get" action=<?php echo $_SERVER["REQUEST_URI"];?> id="updateAlbum"> 
               <input type="hidden" name="albumChosen" id="albumChosen"> 
               <input type="hidden" name="songChosen" id="songChosen"> 
               <input type="hidden" name="scrollPosition" id="scrollPosition"> 
          </form>


		<form action="addSongVersion.php" method="post">
               <fieldset>
                    <legend>Song Version</legend>

                    <label for="song">Version of:</label>
                    <select name="song" size="1">
                         <option value='0' selected='selected'>--Select a song:--</option>
                         <?php
                              echo songOptions($mysqli, array($defaultSong));
                         ?>
                    </select>

                    <label for="album">Album:</label>
                    <select name="album" size="1" onChange="setAlbum(this.form)">
                         <option value='0' selected='selected'>--Select an album:--</option>
                         <?php
                              echo albumOptions($mysqli, array($defaultAlbum));
                         ?>
                    </select>

                    <label for="name">Version Name:</label>
                    <input type="text" name="name" />

                    <!-- Displays the musicians associated with the selected album: -->
                    <?php
                         echo musicianCheckboxes($mysqli, $defaultAlbum);
                    ?>

                    <label for="lyrics">Lyrics:</label>
                    <textarea name="lyrics" rows="20" cols="80"></textarea>

                    <label for="submit"></label>
                    <input type="submit" value="Add Song Version"/>
               </fieldset>
          </form>

	<?php
}


function displayInstrumentForm($mysqli) {
	?>
		<form  action="addInstrument.php" method="post">
               <fieldset>
                    <legend>Instrument</legend>

                    <label for="type">Type:</label>
                    <input type="text" name="type"/>

                    <label for="submit"></label>
                    <input type="submit" value="Add Instrument"/>
               </fieldset>
          </form>
	<?php
}

function displayRecordLabelForm($mysqli) {

	?>
		<form action="addRecordLabel.php" method="post">
               <fieldset>
                    <legend>Record Label</legend>

                    <label for="name">Name:</label>
                    <input type="text" name="name"/>
     
                    <label for="year_founded">Year Founded:</label>
                    <input type="text" name="year_founded"/>

                    <label for="submit"></label>
                    <input type="submit" value="Add Record Label"/>
               </fieldset>
          </form>

	<?php
}

function displayAlbumForm($mysqli) {

	?>
		<form action="addAlbum.php" method="post">
               <fieldset>
                    <legend>Album</legend>

                    <label for="musician">Musician(s):</label>
                    <select name="musician[]" size=<?PHP echo LIST_SIZE?> multiple="multiple">
                         <?php
                              echo musicianOptions($mysqli, array());
                         ?>        
                    </select>
     
                    <label for="title">Album Title:</label>
                    <input type="text" name="title"/>
     
                    <label for="record_label">Record Label:</label>
				<select name="homestate" size="1">
					<?php
						echo recordLabelOptions($mysqli, array());
					?>
				</select>
     
                    <label for="year">Year Released:</label>
                    <input type="text" name="year"/>

                    <label for="submit"></label>
                    <input type="submit" value="Add Album"/>
               </fieldset>
          </form>

	<?php
}

function displaySongForm($mysqli) {
	?>
          <form action="addSong.php" method="post">
               <fieldset>
                    <legend>Song</legend>

                    <label for="title">Song Title:</label>
                    <input type="text" name="title"/>

                    <label for="musician">Written by:</label>
                    <select name="musician[]" size=<?PHP echo LIST_SIZE?> multiple="multiple">
                         <?php
                              echo musicianOptions($mysqli, array());
                         ?>
                    </select>

                    <label for="year_written">Year Written:</label>
                    <input type="text" name="year_written" />

                    <label for="submit"></label>
                    <input type="submit" value="Add Song"/>
               </fieldset>
          </form>
	<?php
}

function displayInfluenceForm($mysqli) {
	?>
          <form action="addInfluence.php" method="post">
               <fieldset>
                    <legend>Influence(s)</legend>

                    <label for="musician">Musician</label>
                    <select name="musician" size="1">
                    <option value='0'>--Select a Musician--</option>
                         <?php
                              echo musicianOptions($mysqli, array());
                         ?>
                    </select>

                    <label for="influence">Influence(s):</label>
                    <select name="influence[]" size=<?PHP echo LIST_SIZE?> multiple="multiple">
                         <?php
                              echo musicianOptions($mysqli, array());
                         ?>
                    </select>

                    <label for="submit"></label>
                    <input type="submit" value="Add Influence(s)"/>
               </fieldset>
          </form>
	<?php
}

?>
