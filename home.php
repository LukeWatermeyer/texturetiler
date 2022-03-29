<?php

session_start();

?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Home</title>
</head>
<body>
<div id="nav-container">
	<div id="tiler-div">
		<h1>Tile a Texture</h1>
	</div>
	<div id="project-div">
		<h1>New Texture Project</h1>
	</div>
</div>
</body>
<script type="text/javascript">
	window.onload = function() {
		document.getElementById('tiler-div').onclick = () => {window.location = 'tiler';};
	}
</script>
</html>