<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>

<head>
     <title>Browse Musicians:</title>
     <link rel="stylesheet" type="text/css" href="common.css" />

	<style>
		label {display:inline; clear:none}
		select {width:auto; min-width:200px}
	</style>

</head>

<body>

	<?php
	     define ("LIST_SIZE", "7");

		require_once("connect.php");
		require_once("options.php");
		require_once("musicianTable.php");

		$albums = getPost("albums");
		$songs = getPost("songs");
		$musicians = getPost("musicians");
		$influences = getPost("influences");
		$instruments = getPost("instruments");
	?>

	<p><a href="mainPage.html">Return to Main Page</a>
	<h2>Browse Musicians:</h2></p>

	<script type="text/javascript">
	     function autoSubmit(form) {
	          form.submit();
	     }
	</script>

	<div class="row">
		<form action="browseMusicians.php" method="post">
			<fieldset>
				<legend>Filter by:</legend>
				
				<label for="musicians">Influenced:</label>
				<select name="musicians[]" size=<?php echo LIST_SIZE?> multiple="multiple" onChange="autoSubmit(this.form)">
					<option value='0' <?php echo setSelected('0', $musicians)?>>--None:--</option>
					<?php
						echo musicianOptions($mysqli, $musicians);
					?>
				</select>
			</fieldset>
		</form>
	
	
		<form action="browseMusicians.php" method="post">
			<fieldset>
				<legend>Filter by:</legend>
				
				<label for="influences">Influenced by:</label>
				<select name="influences[]" size=<?php echo LIST_SIZE?> multiple="multiple" onChange="autoSubmit(this.form)">
					<option value='0' <?php echo setSelected('0', $influences)?>>--None:--</option>
					<?php
						echo musicianOptions($mysqli, $influences);
					?>
				</select>
			</fieldset>
		</form>


		<form action="browseMusicians.php" method="post">
			<fieldset>
				<legend>Filter by:</legend>
				
				<label for="instruments">Instrument Played:</label>
				<select name="instruments[]" size=<?php echo LIST_SIZE?> multiple="multiple"  onChange="autoSubmit(this.form)">
					<option value='0' <?php echo setSelected('0', $instruments)?>>--None:--</option>
					<?php
						echo instrumentOptions($mysqli, $instruments);
					?>
				</select>
			</fieldset>
		</form>
	</div>

	<div class="row">
		<form action="browseMusicians.php" method="post" id="filter">
			<fieldset>
				<legend>Filter by:</legend>
				
				<label for="albums">Album:</label>
				<select name="albums[]" size=<?php echo LIST_SIZE?> multiple="multiple"  onChange="autoSubmit(this.form)">
					<option value='0' <?php echo setSelected('0', $albums)?>>--None:--</option>
					<?php
						if(containsVals($songs)){
							echo albumOptionsForSong($mysqli, $albums, $songs);
						}
						else {
							echo albumOptions($mysqli, $albums);
						}
					?>
				</select>	
	
				<label for="songs">Song:</label>
				<select name="songs[]" size=<?php echo LIST_SIZE?> multiple="multiple"  onChange="autoSubmit(this.form)">
					<option value='0' <?php echo setSelected('0', $songs)?>>--None:--</option>
					<?php
						if(containsVals($albums)) {
							echo songOptionsForAlbum($mysqli, $songs, $albums);
						}
						else {
							echo songOptions($mysqli, $songs);
						}
					?>
				</select>	
			</fieldset>
		</form>
	</div>

	
	<br>
	<?php

		if(containsVals($albums) and containsVals($songs)) {
			echo musicianTableFilterAlbumSong($mysqli, $albums, $songs);
		}
		else if(containsVals($albums)) {
			echo musicianTableFilterAlbum($mysqli, $albums);
		}
		else if(containsVals($songs)) {
			echo musicianTableFilterSong($mysqli, $songs);
		}
		else if(containsVals($musicians)) {
			echo musicianTableFilterInfluenced($mysqli, $musicians);
		}
		else if(containsVals($influences)) {
			echo musicianTableFilterInfluencedBy($mysqli, $influences);
		}
		else if(containsVals($instruments)){
			echo musicianTableFilterInstrument($mysqli, $instruments);
		}
		else {
			echo musicianTable($mysqli);
		}
	?>	
</body>

</html>
