<?php
session_start();
require('inc/config.php');
if(isset($_SESSION['idsesion'])) {
	header("Location: inicio.php");
} else {
	header("Location: login.php");
}
?>