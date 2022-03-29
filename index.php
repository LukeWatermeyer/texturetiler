<?php

session_start();
if ($_SESSION['valid'] == true) {
	header('location: home');
	exit();
} else {
	header('location: registration/login');
	exit();
}

?>