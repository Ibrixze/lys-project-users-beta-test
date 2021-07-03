<?php 

session_start();
unset($_SESSION['user']);
$_SESSION["flash"] = "Vous êtes bien déconnecté";
header("Location:login.php");