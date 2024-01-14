<?php //This php script will reflect that a manager/administrator has seen an update that has been made to a jobcard
	session_start();
    require_once "manicPDO.php";
	$sql = " UPDATE `jobcard` SET `updatesForManic` = '0' WHERE `jobcard`.`cardID` = :jc;";
    $statemnt = $pdo->prepare($sql);
    $statemnt->execute(array(
    	':jc' => $_GET['jc'],
    ));
    $returnRow = array();
  	echo(json_encode($returnRow));
