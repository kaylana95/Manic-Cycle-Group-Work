<?php //This php script will retrieve post content associated to a specific jobcard when called upon
    session_start();
    require_once"manicPDO.php";
    date_default_timezone_set('Africa/Johannesburg');
    header('Content-Type: application/json; charset=utf-8'); 
    $returnRow = array();
    $result = array();
    $sqlRead = "select * FROM forumpost where postedTo = :JC;";
    $statement1 = $pdo->prepare(
      $sqlRead
    );
    $statement1->execute(array(     
      ':JC' => $_GET['CardID'], 
    ));
    while( $resultRow = $statement1->fetch(PDO::FETCH_ASSOC)){
      $sqlReadUser = "select firstName, secondName FROM user where userID = :uID;";
      $statement2 = $pdo->prepare(
        $sqlReadUser
      );
      $statement2->execute(array(     
        ':uID' => $resultRow['postedBy'], 
      ));
      while( $resultRoww = $statement2->fetch(PDO::FETCH_ASSOC)){
        $result['postedBy'] = htmlentities($resultRow['postedBy']);
        $result['firstName'] = htmlentities($resultRoww['firstName']);
        $result['secondName'] = htmlentities($resultRoww['secondName']);
        $result['postedOn'] = htmlentities($resultRow['postedOn']);
        $result['postedAt'] = htmlentities($resultRow['postedAt']);
        $result['postContent'] = htmlentities($resultRow['postContent']);
        $returnRow[] = $result;
        $result = array();
      }
    }
    echo(json_encode($returnRow));
