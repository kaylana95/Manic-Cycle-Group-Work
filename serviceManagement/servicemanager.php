<?php session_start();
  if(isset($_SESSION['type'])){ //ensures user is valid
    if($_SESSION['type'] != "administrator" && $_SESSION['type'] != "manager"){
      header('Location: ../serviceManagement/servicemanagerMechanic.php');
      return;
    }
  }else{
    header('Location: ../index.php');
    return;
  }
  require_once "manicPDO.php";
  date_default_timezone_set('Africa/Johannesburg');
  if(isset($_POST['newJob'])){ //Takes care of a new jobcard having been created
    $sql = "INSERT INTO `jobcard` (`cardID`, `quoteID`,  `jobNumber`, `invoiceNumber`, `mechanicAssigned`, `bikeID`, `bookedBy`, `briefBikeDescription`, `completed`, `admin`, `dateCreated`, `timeCreated`, `totalPrice`, `totalMechanicCompensation`, `updatesForMechanic`, `updatesForManic`) VALUES (Null, Null, Null, Null, :mAs, :bID,:bB,:bBC,:completed, :ad, :Datet, :Timet, :tP, :tC,:umech,:umanic);";
    $statemnt = $pdo->prepare($sql);
    $statemnt->execute(array(
      ':mAs' =>1, 
      ':bID' =>1, 
      ':bB' =>"",
      ':bBC' =>"",
      ':completed' => 0,
      ':ad' =>"",
      ':Datet' => date("Y-m-d"),
      ':Timet' => date("H:i:s"),
      ':tP' => 0,
      ':tC' => 0,
      ':umech' => 0,
      ':umanic' => 0,
    ));
    $jobcardId = $pdo -> lastInsertId();
	  $_SESSION['JobcardID'] = $jobcardId;
	  header("Location: jobcard.php");
	  return; //This return statement will tedirect the user to the newly created jobcard
  }
  if(isset($_POST['redirectToJob'])){ //handles the redirection of a user to a specific jobcard
    $_SESSION['JobcardID'] = $_POST['redirectToJob'];
    header("Location: jobcard.php");
    return;
  }
  if(isset($_POST['deleteTheJob'])){ //handles the deletion of a specific jobcard
    $sql = "DELETE FROM `jobcard` WHERE `jobcard`.`cardID` = :del;";
    $statemnt = $pdo->prepare($sql);
    $statemnt->execute(array(
      ':del' => $_POST['deleteTheJob'],
    ));
    header("Location: servicemanager.php");
    return;
  }
?>

<html>
  <head>
    <link type="text/css" rel="stylesheet" href="servicemanager.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src = http://code.jquery.com/jquery-3.3.1.js>
    </script>
    <title> Service Manager </title>
    <script>
      function logout(){ //handles the logging out of a specific user with an alert message for confirmation
        var confirmBool = confirm("Are you sure you want to logout?");
        if(confirmBool){
          window.location.href="../logoutController.php";
          return true;
        }else{
          return false;
        }
      }
    </script>
  </head>
  <header>
    <div class="container-fluid">
      <div class="row p-2">
        <div class="col-sm-4">
          <a href=""><img src="images/logo.png" alt="company_logo" id="logoImg"></a>
        </div>
        <div class="col-sm-4">
          <h1 style="text-align: center;">Service Manager</h1>
        </div>
        <div class="col-sm-4">
        </div>
      </div>
    </div>
    <nav class="navbar navbar-expand-sm fixed-bottom">
      <hr style="height: 2px; background-color: gray;">
        <ul class="navbar-nav nav-tabs">
          <?php 
            if(isset($_SESSION['type'])){
              if($_SESSION['type'] == "administrator"){
          ?>
            <li class="nav-item">
              <a style="color: #f1ff87;" href="../UserManagement/userManagementView.php" class="nav-link"><h4>Users</h4></a>
            </li>
          <?php
              }
            }
          ?>
            <li class="nav-item">
              <a style="color: #3b3b3b;" href="" class="nav-link  active"><h4>Services</h4></a>
            </li>
            <li class="nav-item">
              <a style="color: #f1ff87;" href="../stockManagement/Home.php" class="nav-link"><h4>Stock</h4></a>
            </li>
            <li class="nav-item">
              <a style="color: #f1ff87;" href="../quoteManagement/quoteManagement.php" class="nav-link"><h4>Quote</h4></a>
            </li>
            <li class="nav-item">
              <a style="color: #f1ff87;" href="../BicycleManagement/BicycleManagementView.php" class="nav-link"><h4>Bicycles</h4></a>
            </li>
            <li class="nav-item ml-5">
              <a style="color: #82ffc3;" onclick="logout()" class="nav-link"><h4>Logout</h4></a>
            </li>   
            <li class="nav-item">
              <a style="color: #82ffc3;" href="../userManual.php" class="nav-link"><h4>Help</h4></a>
            </li>
          </ul>
          <hr style="height: 2px; background-color: gray;">
    </nav>
    </header>
  <body>
    <form action="servicemanager.php" method = "POST">
	    <input type = "hidden" id = "newJob" name = "newJob" value = "NewJB!">
      <input type="submit" id ="newJB" value="Create Jobcard" style = "right:35%; top:15.5%;"/>
    </form>
    <div id = "areabottom" >
      <?php //This php script will populate the list of jobcard information
        $sql = "select * FROM jobcard;";
        $statement = $pdo->prepare(
          $sql
        );
        $statement->execute(array(     
        ));
        while( $resultRow = $statement->fetch(PDO::FETCH_ASSOC)){
          $sqlQ = "select clientID FROM bicycle WHERE bicycleID = :bID;";
          $innerStatement = $pdo->prepare(
            $sqlQ
          );
          $innerStatement->execute(array(  
            ':bID' => $resultRow['bikeID'],
          ));
          $rRow = $innerStatement->fetch(PDO::FETCH_ASSOC);
          $sqlQQ = "select * FROM client WHERE clientID = :cID;";
          $innerStatement2 = $pdo->prepare(
            $sqlQQ
          );
          $innerStatement2->execute(array(  
            ':cID' => $rRow['clientID'],
          ));
          $rrRow = $innerStatement2->fetch(PDO::FETCH_ASSOC);
          $sqlQQQ = "select * FROM user WHERE userID = :uID;";
          $innerStatement3 = $pdo->prepare(
            $sqlQQQ
          );
          $innerStatement3->execute(array(  
            ':uID' => $resultRow['mechanicAssigned'],
          ));
          $rrrRow = $innerStatement3->fetch(PDO::FETCH_ASSOC);
          echo(" <center><div class = \"jobcardInList\">
 <table style = \"table-layout: fixed; width: 100%; text-align: center; border-collapse: collapse;border: 1px solid black;\">
    <tr style = \" border: 1px solid black;\">");
          if($resultRow['updatesForManic'] == 0){
            echo("<td style = \"width:20%;\">Changes:</td><td>No unseen updates related to this jobcard have been made</td>");
          }else{
            echo("<td style = \"width:20%;\">Changes:</td><td style = \"color:darkred; text-decoration: underline; text-decoration-color: red;\"><strong>Unseen updates related to this jobcard have been made</strong></td>");
          }
          echo("
<td>
<form method = \"POST\"> 

    <input type=\"submit\" value=\"View Jobcard\" class = \"viewJobcard\" style = \"height:100%;\">
   <input type = \"hidden\" id = \"redirectToJob\" name = \"redirectToJob\" value = \"".$resultRow['cardID']."\">

</form>
</td>
<td>
<form method = \"POST\" onsubmit=\"return confirm('You are about to delete a Jobcard');\"> 
        <input type=\"submit\" value=\"Delete Jobcard\" class = \"deleteJobcard\" >
        <input type = \"hidden\" id = \"deleteTheJob\" name = \"deleteTheJob\" value = \"".$resultRow['cardID']."\">

</form>
</td>

</tr>
 <tr style = \" border: 1px solid black;\">
        <td style = \"width:20%;\">Client Name:</td><td>".htmlentities($rrRow['clientFullname'])."</td>
        <td style = \"width:20%;\">Date:</td><td>".$resultRow['dateCreated']."</td></tr>
    <tr style = \" border: 1px solid black;\">
        <td style = \"width:20%;\">Telephone Number:</td><td>".$rrRow['phoneNumber']."</td>
        <td style = \"width:20%;\">Mechanic:</td><td>".htmlentities($rrrRow['firstName'])." ".htmlentities($rrrRow['secondName'])."</td></tr>
    <tr style = \" border: 1px solid black;\">
        <td style = \"width:20%;\">Email:</td><td>".htmlentities($rrRow['emailAddress'])."</td>");
          if($resultRow['completed'] == 0){
            echo("<td style = \"width:20%;\">Status:</td><td>In Progress</td></tr>");
          }else{
            echo("<td style = \"width:20%;\">Status:</td><td>Completed</td></tr>");
          }
          echo("<tr style = \" border: 1px solid black;\"><td style = \"width:20%;\">Job Number:</td><td>".$resultRow['jobNumber']."</td><td style = \"width:20%;\">Invoice Number:</td><td>".$resultRow['invoiceNumber']."</td></tr></table></div></center><br>");
        }
      ?>
    </div>
    <div id = "search">
      <table style = "table-layout: fixed; width: 100%;height: 100%; text-align: center;">
        <tr>
          <td  style = "width:80%;">
            <input id = "searchbar" type = "text" name = "searchbar" placeholder = "Search for Jobcard by number...." style = "width:90%;height:80%;">
          </td>
          <td>
            <button id = "submit" type = "submit" onclick = "searchJN()" >Search</button>
         </td>
        </tr>
      </table>
    </div>
    <div id = "filter">
      <table style = "table-layout: fixed; width: 100%;height: 100%; color:#ffffff;">
        <thead>
          <th style = "text-align: center; color :lightgreen;" colspan = "6" >Filter Jobcards:</th>
        </thead>
        <tr>
          <td style = "width:10%;"><input id = "nofilter" name = "filt" type = "radio" value = "nofilt" onclick = "noFilt()"  checked></td>
          <td  style = "text-align: center;"colspan = "2">No Filter</td>
          <td style = "width:10%;"><input id = "unseen" name = "filt" type = "radio" value = "unseen" onclick = "unseen()" ></td>
          <td  style = "text-align: center;" colspan = "2">Unseen Updates</td>
        </tr>
        <tr>
          <td style = "width:10%;"><input id = "completedJobcards" name = "filt" type = "radio" value = "compl" onclick = "compl()" ></td>
          <td  style = "text-align: center;"colspan = "2">Completed</td>
          <td style = "width:10%;"><input id = "incompletedJobcards" name = "filt" type = "radio" value = "inc" onclick = "inc()" ></td>
          <td  style = "text-align: center;" colspan = "2">In Progress</td>
        </tr>
      </table> 
    </div>
    <script type="text/javascript">
      function clearFilter(){ //This funciton will clear the filter area and set it back to its selected 'no filter' status
        document.getElementById("filter").innerHTML = '<table style = "table-layout: fixed; width: 100%;height: 100%; color:#ffffff;"><thead><th style = "text-align: center; color :lightgreen;" colspan = "6" >Filter Jobcards:</th></thead><tr><td style = "width:10%;"><input id = "nofilter" name = "filt" type = "radio" value = "nofilt" onclick = "noFilt()"  checked></td><td  style = "text-align: center;"colspan = "2">No Filter</td><td style = "width:10%;"><input id = "unseen" name = "filt" type = "radio" value = "unseen" onclick = "unseen()" ></td><td  style = "text-align: center;" colspan = "2">Unseen Updates</td></tr><tr><td style = "width:10%;"><input id = "completedJobcards" name = "filt" type = "radio" value = "compl" onclick = "compl()" ></td><td  style = "text-align: center;"colspan = "2">Completed</td><td style = "width:10%;"><input id = "incompletedJobcards" name = "filt" type = "radio" value = "inc" onclick = "inc()" ></td><td  style = "text-align: center;" colspan = "2">In Progress</td></tr></table>';
      }
      function clearSearch(){ //This funciton will clear the search bar and set it back to its default status with no searched term inserted
        document.getElementById("search").innerHTML = '<table style = "table-layout: fixed; width: 100%;height: 100%; text-align: center;"><tr><td  style = "width:80%;"><input id = "searchbar" type = "text" name = "searchbar" placeholder = "Search for Jobcard by number...." style = "width:90%;height:80%;"></td><td><button id = "submit" type = "submit" style = "width:90%;height:80%;" onclick = "searchJN()" >Search</button></td></tr></tr></table>'
      }
      function searchJN(){ //This function will be called upon when a search for a jobcard has been made
        JN =  document.getElementById("searchbar").value;
        clearFilter();
        jcSearch();
      }
      function jcSearch(){ //This function will be called by the searchJN function after a search for a jobcard has been made
        $.getJSON('jobcardRetrieve.php', function(rowz){
          document.getElementById("areabottom").innerHTML ="";
          for (var i = 0; i < rowz.length; i++) {
            arow = rowz[i];
            cardID = arow['cardID'] ;
            quoteID = arow['quoteID'] ;
            jobNumber = arow['jobNumber'];
            invoiceNumber = arow['invoiceNumber'] ;
            mechanicAssigned = arow['mechanicAssigned'] ;
            bikeID = arow['bikeID'] ;
            bookedBy = arow['bookedBy'];
            briefBikeDescription = arow['briefBikeDescription'] ;
            completed = arow['completed'] ;
            admin = arow['admin'] ;
            dateCreated = arow['dateCreated'];
            timeCreated = arow['timeCreated'] ;
            totalPrice =  arow['totalPrice'] ;
            totalMechanicCompensation = arow['totalMechanicCompensation'];
            updatesForMechanic = arow['updatesForMechanic'];
            updatesForManic =  arow['updatesForManic'];
            clientFullname = arow['clientFullname'];
            clientPhoneNumber = arow['phoneNumber'];
            mechanicFirstName = arow['firstName'];
            mechanicSecondName = arow['secondName'];
            clientEmail = arow['emailAddress'];
            mechanicID = arow['userID'];
            updateForManic = arow['updatesForManic'];   
            if(jobNumber == JN){            
              jcsUpdate();
            }
          }
        });
      }
      function jcsUpdate(){ //This function is called by the jcSearch and the various functions associated to diffrent filters after a search for a jobcard has been submitted or a filter has been applied
        written = '';
        written = written+'<center><div class = "jobcardInList"> <table style = "table-layout: fixed; width: 100%; text-align: center; border-collapse: collapse;border: 1px solid black;"><tr style = " border: 1px solid black;">';
        if(updateForManic == 0){
           written = written+'<td style = "width:20%;">Changes:</td><td>No unseen updates related to this jobcard have been made</td>';
        }else{
          written = written+'<td style = "width:20%;">Changes:</td><td style = "color:darkred; text-decoration: underline; text-decoration-color: red;"><strong>Unseen updates related to this jobcard have been made</strong></td>';
        }
        written = written+'<td><form method = "POST"><input type="submit" value="View Jobcard" class = "viewJobcard" style = "height:100%;"><input type = "hidden" id = "redirectToJob" name = "redirectToJob" value = "'+cardID+'"></form></td><td><form method = "POST"><input type="submit" value="Delete Jobcard" class = "deleteJobcard"> <input type = "hidden" id = "deleteTheJob" name = "deleteTheJob" value = "'+cardID+'"></form></td></tr><tr style = " border: 1px solid black;"><td style = "width:20%;">Client Name:</td><td>'+clientFullname+'</td><td style = "width:20%;">Date:</td><td>'+dateCreated+'</td></tr><tr style = " border: 1px solid black;"><td style = "width:20%;">Telephone Number:</td><td>'+clientPhoneNumber+'</td><td style = "width:20%;">Mechanic:</td><td>'+mechanicFirstName+' '+mechanicSecondName+'</td></tr><tr style = " border: 1px solid black;"><td style = "width:20%;">Email:</td><td>'+clientEmail+'</td>';
        if(completed == 0){
          written = written+'<td style = "width:20%;">Status:</td><td>In Progress</td></tr>';
        }else{
          written = written+'<td style = "width:20%;">Status:</td><td>Completed</td></tr>';
        }
        written = written+'<tr style = " border: 1px solid black;"><td style = "width:20%;">Job Number:</td><td>'+jobNumber+'</td><td style = "width:20%;">Invoice Number:</td><td>'+invoiceNumber+'</td></tr></table></div></center><br>';
        document.getElementById("areabottom").innerHTML = document.getElementById("areabottom").innerHTML+ written;
      }
      function noFilt(){ //This function executes after 'no filter' filter option has been selected
        clearSearch();
        jcretrieveNoFilt();
      }
      function jcretrieveNoFilt(){ //This function is called by the noFilt function and executes after 'no filter' filter option has been selected
        $.getJSON('jobcardRetrieve.php', function(rowz){
          document.getElementById("areabottom").innerHTML ="";
          for (var i = 0; i < rowz.length; i++) {
            arow = rowz[i];
            cardID = arow['cardID'] ;
            quoteID = arow['quoteID'] ;
            jobNumber = arow['jobNumber'];
            invoiceNumber = arow['invoiceNumber'] ;
            mechanicAssigned = arow['mechanicAssigned'] ;
            bikeID = arow['bikeID'] ;
            bookedBy = arow['bookedBy'];
            briefBikeDescription = arow['briefBikeDescription'] ;
            completed = arow['completed'] ;
            admin = arow['admin'] ;
            dateCreated = arow['dateCreated'];
            timeCreated = arow['timeCreated'] ;
            totalPrice =  arow['totalPrice'] ;
            totalMechanicCompensation = arow['totalMechanicCompensation'];
            updatesForMechanic = arow['updatesForMechanic'];
            updatesForManic =  arow['updatesForManic'];
            clientFullname = arow['clientFullname'];
            clientPhoneNumber = arow['phoneNumber'];
            mechanicFirstName = arow['firstName'];
            mechanicSecondName = arow['secondName'];
            clientEmail = arow['emailAddress'];
            mechanicID = arow['userID'];
            updateForManic = arow['updatesForManic'];               
            jcsUpdate();
          }
        });
      }
      function unseen(){ //This function executes after 'unseen updates' filter option has been selected
        clearSearch();
        jcretrieveUnseen();
      }
      function jcretrieveUnseen(){ //This function will be called by the 'unseen' function and executes after 'unseen updates' filter option has been selected
        $.getJSON('jobcardRetrieve.php', function(rowz){
          document.getElementById("areabottom").innerHTML ="";
          for (var i = 0; i < rowz.length; i++) {                 
            arow = rowz[i];
            cardID = arow['cardID'] ;
            quoteID = arow['quoteID'] ;
            jobNumber = arow['jobNumber'];
            invoiceNumber = arow['invoiceNumber'] ;
            mechanicAssigned = arow['mechanicAssigned'] ;
            bikeID = arow['bikeID'] ;
            bookedBy = arow['bookedBy'];
            briefBikeDescription = arow['briefBikeDescription'] ;
            completed = arow['completed'] ;
            admin = arow['admin'] ;
            dateCreated = arow['dateCreated'];
            timeCreated = arow['timeCreated'] ;
            totalPrice =  arow['totalPrice'] ;
            totalMechanicCompensation = arow['totalMechanicCompensation'];
            updatesForMechanic = arow['updatesForMechanic'];
            updatesForManic =  arow['updatesForManic'];
            clientFullname = arow['clientFullname'];
            clientPhoneNumber = arow['phoneNumber'];
            mechanicFirstName = arow['firstName'];
            mechanicSecondName = arow['secondName'];
            clientEmail = arow['emailAddress'];
            mechanicID = arow['userID'];
            updateForManic = arow['updatesForManic'];   
            if( updatesForManic == 1){            
              jcsUpdate();
            }
          }
        });
      }
      function inc(){ //This function executes after 'In Progress' filter option has been selected
        clearSearch();
        jcretrieveinc();
      }
      function jcretrieveinc(){ //This function is called by the 'inc' function and executes after 'In Progress' filter option has been selected
        $.getJSON('jobcardRetrieve.php', function(rowz){
          document.getElementById("areabottom").innerHTML ="";
          for (var i = 0; i < rowz.length; i++) {                 
            arow = rowz[i];
            cardID = arow['cardID'] ;
            quoteID = arow['quoteID'] ;
            jobNumber = arow['jobNumber'];
            invoiceNumber = arow['invoiceNumber'] ;
            mechanicAssigned = arow['mechanicAssigned'] ;
            bikeID = arow['bikeID'] ;
            bookedBy = arow['bookedBy'];
            briefBikeDescription = arow['briefBikeDescription'] ;
            completed = arow['completed'] ;
            admin = arow['admin'] ;
            dateCreated = arow['dateCreated'];
            timeCreated = arow['timeCreated'] ;
            totalPrice =  arow['totalPrice'] ;
            totalMechanicCompensation = arow['totalMechanicCompensation'];
            updatesForMechanic = arow['updatesForMechanic'];
            updatesForManic =  arow['updatesForManic'];
            clientFullname = arow['clientFullname'];
            clientPhoneNumber = arow['phoneNumber'];
            mechanicFirstName = arow['firstName'];
            mechanicSecondName = arow['secondName'];
            clientEmail = arow['emailAddress'];
            mechanicID = arow['userID'];
            updateForManic = arow['updatesForManic'];   
            if( completed == 0){            
              jcsUpdate();
            }
          }
        });
      }

      function compl(){ //This function executes after 'Completed' filter option has been selected
        clearSearch();
        jcretrievecompl();
      }
      
      function jcretrievecompl(){ //This function is called by the 'compl' function and executes after 'Completed' filter option has been selected
        $.getJSON('jobcardRetrieve.php', function(rowz){
          document.getElementById("areabottom").innerHTML ="";
          for (var i = 0; i < rowz.length; i++) {                 
            arow = rowz[i];
            cardID = arow['cardID'] ;
            quoteID = arow['quoteID'] ;
            jobNumber = arow['jobNumber'];
            invoiceNumber = arow['invoiceNumber'] ;
            mechanicAssigned = arow['mechanicAssigned'] ;
            bikeID = arow['bikeID'] ;
            bookedBy = arow['bookedBy'];
            briefBikeDescription = arow['briefBikeDescription'] ;
            completed = arow['completed'] ;
            admin = arow['admin'] ;
            dateCreated = arow['dateCreated'];
            timeCreated = arow['timeCreated'] ;
            totalPrice =  arow['totalPrice'] ;
            totalMechanicCompensation = arow['totalMechanicCompensation'];
            updatesForMechanic = arow['updatesForMechanic'];
            updatesForManic =  arow['updatesForManic'];
            clientFullname = arow['clientFullname'];
            clientPhoneNumber = arow['phoneNumber'];
            mechanicFirstName = arow['firstName'];
            mechanicSecondName = arow['secondName'];
            clientEmail = arow['emailAddress'];
            mechanicID = arow['userID'];
            updateForManic = arow['updatesForManic'];   
            if( completed == 1){            
              jcsUpdate();
            }
          }
        });
      }
    </script>
  </body>
</html>