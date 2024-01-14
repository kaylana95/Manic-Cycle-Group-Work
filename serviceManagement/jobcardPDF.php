<?php session_start();
    require_once"manicPDO.php";
?>
<html>
<head>
<link type="text/css" rel="stylesheet" href="jobcardPDF.css">
<script defer src = "jobcard.js"></script>
<title>Jobcard PDF</title>
</head>

<!--
This php document is responsible for displying two tables reflecting a specific jobcard's information. All php related code is thus simply aimed at populating these two tables with the appropriate information.
-->

<body>
  <?php 
    echo ("<div id = \"jobcardInfoArea\"class = \"jobcardInfoArea\"><center><table style = \"table-layout: fixed; width: 100%; height:100%; text-align: center; border-collapse: collapse;border: 1px solid black;\"> <tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Client Name:</strong></td><td colspan=\"2\">");
    $sql1 = "select * FROM jobcard where cardID = :cID;";
    $statement1 = $pdo->prepare(
      $sql1
    );
    $statement1->execute(array(     
      ':cID' => $_SESSION['JobcardID'], 
    ));
    while( $resultRow = $statement1->fetch(PDO::FETCH_ASSOC)){
      $sqlQ = "select * FROM bicycle WHERE bicycleID= :bID;";
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
          echo($resultRow3['clientFullname']);
          echo("</td><td style = \"width:20%;\"><strong>Date:</strong></td><td colspan=\"2\">");
          echo($resultRow['dateCreated']);
          echo("</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Client Phone Number:</strong></td><td colspan=\"2\">");
          echo($resultRow3['phoneNumber']);
          echo("</td><td style = \"width:20%;\"><strong>Mechanic:</strong></td><td colspan=\"2\">");
          $sqluser = "select * FROM user WHERE userID= :uID;";
          $innerstatementuser = $pdo->prepare(
            $sqluser
          );
          $innerstatementuser->execute(array(    
            ':uID' => $resultRow['mechanicAssigned'], 
          ));
          while( $resultRoww = $innerstatementuser->fetch(PDO::FETCH_ASSOC)){
            echo($resultRoww['firstName']. " ". $resultRoww['secondName']);
            echo("</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Client Email:</strong></td><td colspan=\"2\">");
            echo( $resultRow3['emailAddress']);
            echo("</td><td style = \"width:20%;\"><strong>Status:</strong></td><td colspan=\"2\">");
            if($resultRow['completed'] == 0){
              echo("In progress");
            }else{
              echo("Completed");
            }
            echo("</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Job Number:</strong></td><td>");
            echo($resultRow['jobNumber']);
            echo("</td><td style = \"width:20%;\"><strong>Quote Number:</strong></td><td>");
            echo($resultRow['quoteID']);
            echo("</td><td style = \"width:20%;\"><strong>Invoice Number:</strong></td><td>");
            echo($resultRow['invoiceNumber']);
            echo("</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Admin:</strong></td><td colspan=\"2\">");
            echo($resultRow['admin']);
            echo("</td><td style = \"width:20%;\"><strong>Booked By:</strong></td><td colspan=\"2\">");
            echo($resultRow['bookedBy']);
            echo("</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Bike Detail:</strong></td><td colspan=\"5\">");
            echo($resultRow['briefBikeDescription']);
          }
        }
      }
    }
  ?></td></tr></table></center></div>
  <div class = "taskArea" >
    <?php 
      $sql = "select * FROM task where cardID = :cID;";
      $statement = $pdo->prepare(
        $sql
      );
      $statement->execute(array(     
        ':cID' => $_SESSION['JobcardID'], 
      ));
      $mechanicTotal = 0;
      $clientTotal = 0;
      while( $resultRow = $statement->fetch(PDO::FETCH_ASSOC)){
	      $mechanicTotal = $mechanicTotal + $resultRow['mechanicCompensation']*$resultRow['quantity'];
	      $clientTotal = $clientTotal + $resultRow['costToCustomer']*$resultRow['quantity'];
        $sql = "UPDATE `task` SET `quantitativeTotalCostToCustomer` = :qTC, `quantitativeTotalMechanicCompensation` = :qTM WHERE `task`.`taskID` = :tID;";
        $statemnt = $pdo->prepare($sql);
        $statemnt->execute(array(
          ':qTC' => ($resultRow['costToCustomer']*$resultRow['quantity']),
          ':qTM' => ($resultRow['mechanicCompensation']*$resultRow['quantity']),
          ':tID' => $resultRow['taskID'],
        ));
      }
      $sql = "UPDATE `jobcard` SET `totalPrice` = :tP, `totalMechanicCompensation` = :tC WHERE `jobcard`.`cardID` = :jC;";
      $statemnt = $pdo->prepare($sql);
      $statemnt->execute(array(
        ':jC' => $_SESSION['JobcardID'],
        ':tP' => $clientTotal,
        ':tC' => $mechanicTotal,
      ));
    ?>
    <div>
  	  <table style = "width:100%; border-collapse: collapse; border: 1px solid black; font-weight:bold;background-color: #DEFFD9;">
        <tr>
          <td>Total Cost To Customer:</td>
          <td><?php echo("R".$clientTotal);?></td>
          <td>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</td>
          <td>Total Mechanic Compensation:</td>
          <td><?php echo("R".$mechanicTotal);?></td>
        </tr>
      </table>
	  </div>
    <br>
    <?php 
      $sql = "select * FROM task where cardID = :cID;";
      $statement = $pdo->prepare(
        $sql
      );
      $statement->execute(array(     
        ':cID' => $_SESSION['JobcardID'], 
      ));
      $taskCount = 1;
      echo("<div style = \"background-color: #DEFFD9;\" id = \"task");
      echo("\">"); 
      echo("
   <table style = \"table-layout: fixed; width: 100%; text-align: center; border-collapse: collapse;border: 3px solid black;\">
    <th>Task</th><th colspan = \"3\">Stock Used <br>/ Labour Charge:</th><th>Quantity:</th><th>Cost To Customer:</th><th>Subtotal:</th><th>Mechanic Compensation:</th><th>Subtotal:</th><th>Code:</th>");
      while( $resultRow = $statement->fetch(PDO::FETCH_ASSOC)){
        echo("<tr style = \" border: 1px solid black;\">
        <td>".$taskCount."</td><td  colspan = \"3\">". $resultRow['stockUsedAndLabourCharge']."</td><td>".$resultRow['quantity']."</td><td>R".$resultRow['costToCustomer']."</td><td>R".$resultRow['costToCustomer'] * $resultRow['quantity']."</td><td>R".$resultRow['mechanicCompensation']."</td><td>R".$resultRow['mechanicCompensation']*$resultRow['quantity']."</td><td>".$resultRow['code']."</td></tr>");
        $taskCount++;
      }
      echo("</table>");
    ?>
  </div>
</body>
</html>