<?php
include "functions//functions.php";
session_start();
	if (!empty($_SESSION['auth']) and $_SESSION['auth']) {
		session_destroy();
		setcookie('login', '', time());
		setcookie('key', '', time());
		$url = url();
		header("Location: $url");
	}
?>