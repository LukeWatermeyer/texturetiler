<?php

session_start();

if (isset($_FILES['textureFile'])) {
	$target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES['textureFile']["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	if(isset($_POST["submit"])) {
		$check = getimagesize($_FILES['textureFile']["tmp_name"]);
		if($check !== false) {
			// echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
			// Check if file already exists
			if (file_exists($target_file)) {
				echo "Sorry, file already exists.";
				$uploadOk = 0;
			} else {
				// Check file size
				if ($_FILES["textureFile"]["size"] > 50000000) {
					echo "Sorry, your file is too large.";
					$uploadOk = 0;
				} else {
					// Allow certain file formats
					if($imageFileType != "png") {
						echo "Sorry, only PNG files are allowed.";
						$uploadOk = 0;
					}
				}
			}
		} else {
			echo "File is not an image.";
			$uploadOk = 0;
		}
	}

		// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
	} else {
		if (move_uploaded_file($_FILES["textureFile"]["tmp_name"], $target_file)) {
			genTiler('uploads/' . $_FILES['textureFile']['name']);
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}
} elseif (isset($_GET['ipath'])) {
	genTiler($_GET['ipath']);
}

function genTiler($path) {
	echo "<div id='tiler-container'><div id='tiler-div' data-path='" . $path . "'>";
	for ($i = 0; $i < 16; $i++) {
    	echo "<img src='" . $path . "' class='tiled-img'>";
    }
	echo '</div><div id="slider-container">
		<input type="range" min="1" max="9" value="4" class="slider" id="tiler-slider">
	</div>
	<p id="slider-value"></p></div>';
}

?>

<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/tiler-style.css">
	<title>Texture Tiler</title>
</head>
<body>
	<div id="topbar"><h1 id="open-modal">+</h1></div>
	<div id="modal">
		<div id="tiler-menu">
		<div id="upload-form">
			<h3>Upload a New Image</h3>
			<form action="tiler.php" method="post" enctype="multipart/form-data">
				<input type="file" name="textureFile" id="textureFile" class="new-file-input">
				<input type="submit" value="Tile" name="submit" style="margin-top: 12px;" id="submit-btn" class="submit-btn" disabled>
			</form>
		</div>
		<hr>
		<div id="existing-imgs">
			<h3>Choose an Uploaded Image</h3>
			<?php

			$path = "uploads";

			if ($dir = opendir($path)) {
				while (($file = readdir($dir)) !== false) {
					if ($file == '.' || $file == '..') {
						continue;
					}
					echo "<div class='img-container' data-path='" . $path . "/" . $file . "'><img class='up-imgs' src='" . $path . "/" . $file . "'></div>";
				}
				closedir($dir);
			}

			?>
			<div id="more-arrow">˅</div>
		</div>
		</div>
	</div>
</body>

<script type="text/javascript">
	window.onload = function() {
		var imagesList = document.getElementsByClassName('img-container');
		var uploadedImgsContainer = document.getElementById('existing-imgs');
		var moreArrow = document.getElementById('more-arrow');
		var modal = document.getElementById('modal');
		var tilerDiv = document.getElementById('tiler-div');

		if (elemOverflowsY(uploadedImgsContainer)) {
			moreArrow.classList.add('show');
		}

		uploadedImgsContainer.onscroll = function() {
			if (!scrolledToBottom(uploadedImgsContainer)) {
				moreArrow.classList.add('show');
			} else {
				moreArrow.classList.remove('show');
			}
		}

		document.getElementById('textureFile').onchange = function() {
			if (document.getElementById('textureFile').value != '') {
				document.getElementById('submit-btn').disabled = false;
			}
		}

		for (const img of imagesList) {
			img.onclick = function() {
				window.location = 'tiler?ipath=' + img.getAttribute('data-path');
			}
		}

		document.getElementById('open-modal').onclick = function() {
			modal.classList.add('open');
		}

		window.onclick = function(event) {
			if (event.target == modal) {
				modal.classList.remove('open');
			}
		}

		if (tilerDiv) {
			var slider = document.getElementById("tiler-slider");
			var sliderValue = document.getElementById("slider-value");
			sliderValue.innerHTML = (slider.value)**2;

			slider.oninput = function() {
			  var sliderVal = (this.value)**2;
			  sliderValue.innerHTML = sliderVal;
			  regenTiles(sliderVal)
			}
		}
	}

	function regenTiles(numTiles) {
		var tilerDiv = document.getElementById('tiler-div');

		var cssRoot = document.querySelector(':root');
		cssRoot.style.setProperty('--tile-size', 512/(Math.sqrt(numTiles)));

		var tilerDivHtml = "";
		for (var i = 0; i < numTiles; i++) {
			tilerDivHtml += "<img src='" + tilerDiv.getAttribute('data-path') + "' class='tiled-img'>";
		}
		tilerDiv.innerHTML = tilerDivHtml;
	}

	function elemOverflowsY(elem) {
		return elem.scrollHeight > elem.clientHeight;
	}

	function scrolledToBottom(elem) {
		return elem.scrollTop == (elem.scrollHeight - elem.offsetHeight);
	}
</script>
</html>