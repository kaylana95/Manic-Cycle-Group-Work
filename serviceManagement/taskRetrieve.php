<?php //This php script will retrieve a list of information associated to various tasks associated to jobcards
  session_start();
  require_once"manicPDO.php";
  date_default_timezone_set('Africa/Johannesburg');
  header('Content-Type: application/json; charset=utf-8'); 
  $returnRow = array();
  $sql = "select * From task;";
  $statement = $pdo->prepare(
    $sql
  );
  $statement->execute(array(
  ));
  while ($resultRow = $statement->fetch(PDO::FETCH_ASSOC)) {
    $resultRow['taskID'] = htmlentities($resultRow['taskID']);
    $resultRow['cardID'] = htmlentities($resultRow['cardID']);
    $resultRow['stockUsedAndLabourCharge'] = htmlentities($resultRow['stockUsedAndLabourCharge']);
    $resultRow['quantity'] = htmlentities($resultRow['quantity']);
    $resultRow['costToCustomer'] = htmlentities($resultRow['costToCustomer']);
    $resultRow['mechanicCompensation'] = htmlentities($resultRow['mechanicCompensation']);
    $resultRow['code'] = htmlentities($resultRow['code']);
    $resultRow['completed'] = htmlentities($resultRow['completed']);
    $returnRow[] = $resultRow;
  }
  echo(json_encode($returnRow));
