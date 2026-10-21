<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>ImageFlow</title>
		<meta name="robots" content="index, follow, noarchive" />
		<link rel="stylesheet" href="style.css" type="text/css" />

		<!-- This includes the ImageFlow CSS and JavaScript -->
		<link rel="stylesheet" href="imageflow.css" type="text/css" />
		<script type="text/javascript" src="imageflow.packed.js"></script>

	</head>
	<body>
		<!-- This is all the XHTML ImageFlow needs -->
		<div id="myImageFlow" class="imageflow">
			<? include('./get_photos.php'); ?>
		</div>
	</body>
</html>