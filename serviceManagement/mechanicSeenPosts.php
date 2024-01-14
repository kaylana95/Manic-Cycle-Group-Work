<?php //This php script update a specific jobcard's status to seen for a mechanic when called
	session_start();
	require_once "manicPDO.php";
	$sql = " UPDATE `jobcard` SET `updatesForMechanic` = '0' WHERE `jobcard`.`cardID` = :jc;";
    $statemnt = $pdo->prepare($sql);
    $statemnt->execute(array(
    	':jc' => $_GET['jc'],
    ));
    $returnRow = array();
  	echo(json_encode($returnRow));

