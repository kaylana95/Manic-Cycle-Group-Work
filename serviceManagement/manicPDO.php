<?php //This PDO will handle the system's connection to the servicemanager database 
	$pdo = new PDO('mysql:host=localhost;port=3306;dbname=servicemanagement','root', '');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>