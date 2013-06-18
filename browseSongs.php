<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>

<head>
     <title>Browse Songs:</title>
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
		require_once("songTable.php");
		
		$musicians = getPost("musicians");
		$albums = getPost("albums");
	?>

	<p><a href="mainPage.html">Return to Main Page</a>
	<h2>Browse Songs:</h2></p>

	<script type="text/javascript">
	     function autoSubmit(form) {
	          document.getElementById("filter").submit();
	     }
	</script>

	<form action="browseSongs.php" method="post" id="filter">
		<fieldset>
			<legend>Filter by:</legend>
			
			<label for "musicians">Musician:</label>
			<select name="musicians[]" size=<?php echo LIST_SIZE?> multiple='multiple' onChange="autoSubmit(this.form)">
				<option value='0' <?php echo setSelected('0', $musicians) ?>>--None:--</option>
				<?php
					if(containsVals($albums)){
						echo musicianOptionsForAlbum($mysqli, $musicians, $albums);
					}
					else {
						echo musicianOptions($mysqli, $musicians);
					}
				?>
			</select>	

			<label for "albums">Album:</label>
			<select name="albums[]" size=<?php echo LIST_SIZE?> multiple='multiple' onChange="autoSubmit(this.form)">
				<option value='0' <?php echo setSelected('0', $albums) ?>>--None:--</option>
				<?php
					if(containsVals($musicians)) {
						echo albumOptionsForMusician($mysqli, $albums, $musicians);
					}
					else {
						echo albumOptions($mysqli, $albums);
					}
				?>
			</select>	
		</fieldset>
	</form>

<br><br>

	<?php
		if(containsVals($musicians) and containsVals($albums)) {
			echo songTableFilterMusicianAlbum($mysqli, $musicians, $albums);
		}
		else if(containsVals($musicians)) {
			echo songTableFilterMusician($mysqli, $musicians);
		}
		else if(containsVals($albums)) {
			echo songTableFilterAlbum($mysqli, $albums);
		}
		else {
			echo songTable($mysqli);
		}
	?>	
</body>

</html>
