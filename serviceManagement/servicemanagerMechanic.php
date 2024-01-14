<?php 
  session_start();
  if(!isset($_SESSION['type'])){//ensures user is valid
    header('Location: ../index.php');
    return;
  }else{
    if($_SESSION['type'] == "administrator" || $_SESSION['type'] == "manager"){
      header('Location: ../serviceManagement/servicemanager.php');
      return;
    }
  }
  require_once "manicPDO.php";
  date_default_timezone_set('Africa/Johannesburg');

  if(isset($_POST['redirectToJob'])){
    $_SESSION['JobcardID'] = $_POST['redirectToJob'];
    header("Location: jobcardMechanic.php");
    return;
  }
  
?>

<html>
  <head>
    <link type="text/css" rel="stylesheet" href="servicemanagerMechanic.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src = http://code.jquery.com/jquery-3.3.1.js>
    </script>
    <script defer src = "servicemanager.js"></script><!--will load after the body loads-->
    <title> Service Manager </title>
    <script>
      function logout(){ //handles the logout operation of a user
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
          <li class="nav-item">
            <a style="color: #3b3b3b;" href="servicemanagerMechanic.php" class="nav-link  active"><h4>Services</h4></a>
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
    <div id = "areabottom" >
      <?php //This php script populates the jobcard list upon the page's first load
        $sql = "select * FROM jobcard where mechanicAssigned = :mA;";
        $statement = $pdo->prepare(
          $sql
        );
        $statement->execute(array(  
          ':mA' => $_SESSION['user'], 
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
          if($resultRow['updatesForMechanic'] == 0){
            echo("<td style = \"padding:0px;\">Changes:</td><td style = \"padding:0px\";>No unseen updates related to this jobcard have been made</td>");
          }else{
            echo("<td style = \"padding:0px;\">Changes:</td><td style = \"color:darkred; text-decoration: underline; text-decoration-color: red; padding:0px;\"><strong>Unseen updates related to this jobcard have been made</strong></td>");
          }
          echo("
    <td colspan = \"2\" style = \"padding:0px;\">
    <form method = \"POST\"> 
        <input type=\"submit\" value=\"View Jobcard\" class = \"viewJobcard\">
       <input type = \"hidden\" id = \"redirectToJob\" name = \"redirectToJob\" value = \"".$resultRow['cardID']."\">
    </form>
    </td>
    </tr>
     <tr style = \" border: 1px solid black;\">
            <td style = \"width:20%;\">Client Name:</td><td>".htmlentities($rrRow['clientFullname'])."</td>
            <td style = \"width:20%;\">Date:</td><td>".$resultRow['dateCreated']."</td></tr>
        <tr style = \" border: 1px solid black;\">
            <td style = \"width:20%;\">Job Number:</td><td>".$resultRow['jobNumber']."</td>");
          if($resultRow['completed'] == 0){
            echo("<td style = \"width:20%;\">Status:</td><td>In Progress</td></tr>");
          }else{
            echo("<td style = \"width:20%;\">Status:</td><td>Completed</td></tr>");
          }
          echo("</table></div></center><br>");
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
            <button id = "submit" type = "submit" >Search</button>
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
      function clearFilter(){ //sets the filer's position back to default
        document.getElementById("filter").innerHTML = '<table style = "table-layout: fixed; width: 100%;height: 100%; color:#ffffff;"><thead><th style = "text-align: center; color :lightgreen;" colspan = "6" >Filter Jobcards:</th></thead><tr><td style = "width:10%;"><input id = "nofilter" name = "filt" type = "radio" value = "nofilt" onclick = "noFilt()"  checked></td><td  style = "text-align: center;"colspan = "2">No Filter</td><td style = "width:10%;"><input id = "unseen" name = "filt" type = "radio" value = "unseen" onclick = "unseen()" ></td><td  style = "text-align: center;" colspan = "2">Unseen Updates</td></tr><tr><td style = "width:10%;"><input id = "completedJobcards" name = "filt" type = "radio" value = "compl" onclick = "compl()" ></td><td  style = "text-align: center;"colspan = "2">Completed</td><td style = "width:10%;"><input id = "incompletedJobcards" name = "filt" type = "radio" value = "inc" onclick = "inc()" ></td><td  style = "text-align: center;" colspan = "2">In Progress</td></tr></table>';
      }
      function clearSearch(){ //sets the search bar's state to default
        document.getElementById("search").innerHTML = '<table style = "table-layout: fixed; width: 100%;height: 100%; text-align: center;"><tr><td  style = "width:80%;"><input id = "searchbar" type = "text" name = "searchbar" placeholder = "Search for Jobcard by number...." style = "width:90%;height:80%;"></td><td><button id = "submit" type = "submit"  onclick = "searchJN()" >Search</button></td></tr></tr></table>'
      }
      function searchJN(){ //deals with the searching of a jobcard after the search button has been clicked
        JN =  document.getElementById("searchbar").value;
        clearFilter();
        jcSearch();
      }
      function jcSearch(){ //retrieves the appropriate informaiton after the search button for a jobcard has veen clicked
        $.getJSON('jobcardRetrieveMechanic.php', function(rowz){
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
      function jcsUpdate(){ //Updates the jobcard list accordingly after a search or filter has been applied
        written = '';
        written = written+'<center><div class = "jobcardInList"> <table style = "table-layout: fixed; width: 100%; text-align: center; border-collapse: collapse;border: 1px solid black;"><tr style = " border: 1px solid black;">';
        if(updatesForMechanic == 0){
          written = written+'<td style = "padding:0px;">Changes:</td><td style = "padding:0px";>No unseen updates related to this jobcard have been made</td>';
        }else{
          written = written+'<td style = "padding:0px;">Changes:</td><td style = "color:darkred; text-decoration: underline; text-decoration-color: red; padding:0px;"><strong>Unseen updates related to this jobcard have been made</strong></td>';
        }
        written = written+'<td colspan = "2" style = "padding:0px;"><form method = "POST"> <input type="submit" value="View Jobcard" class = "viewJobcard">  <input type = "hidden" id = "redirectToJob" name = "redirectToJob" value = "'+cardID+'"></form></td></tr><tr style = " border: 1px solid black;"><td style = "width:20%;">Client Name:</td><td>'+clientFullname+'</td><td style = "width:20%;">Date:</td><td>'+dateCreated+'</td></tr><tr style = " border: 1px solid black;"><td style = "width:20%;">Job Number: </td><td>'+jobNumber+'</td>';
        if(completed == 0){
          written = written+'<td style = "width:20%;">Status:</td><td>In Progress</td></tr>';
        }else{
          written = written+'<td style = "width:20%;">Status:</td><td>Completed</td></tr>';
        }
        written = written+'</table></div></center><br>';
        document.getElementById("areabottom").innerHTML = document.getElementById("areabottom").innerHTML+ written;
      }
      function noFilt(){ //executes after the 'No Filter' filter option has been selected
        clearSearch();
        jcretrieveNoFilt();
      }
      function jcretrieveNoFilt(){ //called by the noFilt() function
        $.getJSON('jobcardRetrieveMechanic.php', function(rowz){
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
      function unseen(){ //executes after the 'Unseen Updates' filter option has been selected
        clearSearch();
        jcretrieveUnseen();
      }
      function jcretrieveUnseen(){ //called by the unseen() function
        $.getJSON('jobcardRetrieveMechanic.php', function(rowz){
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
            if( updatesForMechanic == 1){            
             jcsUpdate();
            }
          }
        });
      }
      function inc(){ //executes after the 'In Progress' filter option has been selected
        clearSearch();
        jcretrieveinc();
      }
      function jcretrieveinc(){ // called by the inc() function
        $.getJSON('jobcardRetrieveMechanic.php', function(rowz){
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
      function compl(){ //executes after the 'Completed' filter option has been selected
        clearSearch();
        jcretrievecompl();
      }
      function jcretrievecompl(){ //called by the compl() function
        $.getJSON('jobcardRetrieveMechanic.php', function(rowz){
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
    </script></div>
  </body>
</html>