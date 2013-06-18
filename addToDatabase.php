<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>

<head>
	<title>Folk Music Database</title>
	<link rel="stylesheet" type="text/css" href="common.css" />

	<!-- If the page has been refreshed after dynamically altering form fields, retains scroll position: -->
	<script type="text/javascript">
		function scroll() {
			window.scrollTo(0, <?php echo $_GET["scrollPosition"]; ?>);
		}
	</script>

</head>

<body <?php if(isset($_GET["scrollPosition"])) {echo "onload='scroll()'";} ?> >

<!--	PHP Header for the file:
	Defines constants, connects to database, includes necessary
	files, and creates wrapper variables for POSTed variables -->

<?php
	define ("LIST_SIZE", "7");
	
	//Connect to the database:
	require_once("connect.php");

	//Include necessary functions:
	require_once("forms.php");
?>


<p><a href="mainPage.html">Return to Main Page</a>
<h2>Add to the Database:</h2></p>


<div class="row">
	<div class="col1">

		<!-- Add a musician to the database: -->

		<?php
			displayMusicianForm($mysqli);
		?>
	
		<!-- Add a song version to the database: -->	
	
		<?php
			displaySongVersionForm($mysqli);
		?>
		
	</div>	
	<div class="col2">

		<!-- Add an instrument to the database -->

		<?php
			displayInstrumentForm($mysqli);
		?>
	
		<!-- Add an album to the database: -->

		<?php
			displayAlbumForm($mysqli);
		?>

	</div>
	<div class="col3">

		<!-- Add a song to the database: -->

		<?php
			displaySongForm($mysqli);
		?>

		<!-- Add influences to the database: -->

		<?php
			displayInfluenceForm($mysqli);
		?>

		<!-- Add record label to the database -->

		<?php
			displayRecordLabelForm($mysqli);
		?>
	</div>
</div>


</body>
</html>



