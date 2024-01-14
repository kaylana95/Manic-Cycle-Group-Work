<?php //This function will be called in order to see whether unseen post updates have occured on the jobcard page 
  session_start();
  require_once"manicPDO.php";
  date_default_timezone_set('Africa/Johannesburg');
  header('Content-Type: application/json; charset=utf-8'); 
  $returnRow = array();
  $sql = "select cardID, updatesForMechanic, updatesForManic From jobcard;";
  $statement = $pdo->prepare(
    $sql
  );
  $statement->execute(array(
  ));
  while ($resultRow = $statement->fetch(PDO::FETCH_ASSOC)) {
    $resultRow['cardID'] = htmlentities($resultRow['cardID']);
    $resultRow['updatesForMechanic'] = htmlentities($resultRow['updatesForMechanic']);
    $resultRow['updatesForManic'] = htmlentities($resultRow['updatesForManic']);
    $returnRow[] = $resultRow;
  }
  echo(json_encode($returnRow));
