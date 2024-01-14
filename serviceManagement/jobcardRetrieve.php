<?php //This php script will retrieve all the information associated jobcards from the database
  session_start();
  require_once"manicPDO.php";
  date_default_timezone_set('Africa/Johannesburg');
  header('Content-Type: application/json; charset=utf-8'); 
  $returnRow = array();
  $sql = "select * From jobcard;";
  $statement = $pdo->prepare(
    $sql
  );
  $statement->execute(array(
  ));
  while ($resultRow = $statement->fetch(PDO::FETCH_ASSOC)) {
    $resultRow['cardID'] = htmlentities($resultRow['cardID']);
    $resultRow['quoteID'] = htmlentities($resultRow['quoteID']);
    $resultRow['jobNumber'] = htmlentities($resultRow['jobNumber']);
    $resultRow['invoiceNumber'] = htmlentities($resultRow['invoiceNumber']);
    $resultRow['mechanicAssigned'] = htmlentities($resultRow['mechanicAssigned']);
    $resultRow['bikeID'] = htmlentities($resultRow['bikeID']);
    $resultRow['bookedBy'] = htmlentities($resultRow['bookedBy']);
    $resultRow['briefBikeDescription'] = htmlentities($resultRow['briefBikeDescription']);
    $resultRow['completed'] = htmlentities($resultRow['completed']);
    $resultRow['admin'] = htmlentities($resultRow['admin']);
    $resultRow['dateCreated'] = htmlentities($resultRow['dateCreated']);
    $resultRow['timeCreated'] = htmlentities($resultRow['timeCreated']);
    $resultRow['totalPrice'] = htmlentities($resultRow['totalPrice']);
    $resultRow['totalMechanicCompensation'] = htmlentities($resultRow['totalMechanicCompensation']);
    $resultRow['updatesForMechanic'] = htmlentities($resultRow['updatesForMechanic']);
    $resultRow['updatesForManic'] = htmlentities($resultRow['updatesForManic']);
    $sqluser = "select userID, emailAddress as mechanicEmail, firstName, secondName FROM user WHERE userID= :uID;";
    $innerstatementuser = $pdo->prepare(
      $sqluser
    );
    $innerstatementuser->execute(array(    
      ':uID' => $resultRow['mechanicAssigned'], 
    ));
    while( $resultRowNew = $innerstatementuser->fetch(PDO::FETCH_ASSOC)){
      $resultRow = array_merge($resultRow, $resultRowNew);
    }
    $sqlQ = "select clientID FROM bicycle WHERE bicycleID= :bID;";
    $innerstatementt = $pdo->prepare(
      $sqlQ
    );
    $innerstatementt->execute(array(    
      ':bID' => $resultRow['bikeID'], 
    ));
    while( $resultRow2 = $innerstatementt->fetch(PDO::FETCH_ASSOC)){
      $sqlQQ = "select * FROM client WHERE clientID= :cID;";
      $innerstatementtt = $pdo->prepare(
        $sqlQQ
      );
      $innerstatementtt->execute(array(    
        ':cID' => $resultRow2['clientID'], 
      ));
      while( $resultRow3 = $innerstatementtt->fetch(PDO::FETCH_ASSOC)){
        $resultRow = array_merge($resultRow, $resultRow3);
        $returnRow[] = $resultRow;
      }
    }
  }
  echo(json_encode($returnRow));
