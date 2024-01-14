<?php 
  session_start();
  if(!isset($_SESSION['type'])){ //ensures valid logged in user
    header('Location: ../index.php');
    return;
  }else{
    if($_SESSION['type'] == "administrator" || $_SESSION['type'] == "manager"){
      header('Location: ../serviceManagement/servicemanager.php');
      return; 
      }
    }
    require_once"manicPDO.php";
    if(isset($_POST['ToggleCompl'])){ //deals with the case where the Completed/in progress status of a task has been changed
      $sql = "select * FROM task where taskID = :tID;";
      $statement = $pdo->prepare(
        $sql
      );
      $statement->execute(array(     
        ':tID' => $_POST['ToggleCompl'], 
      ));
      while( $resultRow = $statement->fetch(PDO::FETCH_ASSOC)){
        if($resultRow['completed']==0){
          $sql = "UPDATE `task` SET `completed` = :comp WHERE `task`.`taskID` = :tID;";
          $statemnt = $pdo->prepare($sql);
          $statemnt->execute(array(
            ':tID' => $_POST['ToggleCompl'], 
            ':comp' => 1,
          ));
          header("Location: jobcardMechanic.php");
          return;
        }
        if($resultRow['completed']==1){
          $sql = "UPDATE `task` SET `completed` = :comp WHERE `task`.`taskID` = :tID;";
          $statemnt = $pdo->prepare($sql);
          $statemnt->execute(array(
            ':tID' => $_POST['ToggleCompl'], 
            ':comp' => 0,
          ));
          header("Location: jobcardMechanic.php");
          return;
        }
      }
    }
    if($_SESSION['type'] == "mechanic"){ //updates the unseen status of a jobcard to seen when the page is first visited 
      $sql = "UPDATE `jobcard` SET `updatesForMechanic`  = '0' WHERE `jobcard`.`cardID` = :JC;";
      $statemnt = $pdo->prepare($sql);
      $statemnt->execute(array(
        ':JC' => $_SESSION['JobcardID'],
      ));
    }else{
      $sql = "UPDATE `jobcard` SET `updatesForManic` = '0' WHERE `jobcard`.`cardID` = :JC;";
      $statemnt = $pdo->prepare($sql);
      $statemnt->execute(array(
        ':JC' => $_SESSION['JobcardID'],
      ));
    }
    if(isset($_POST['searchbar'])){ //handles a new post having been made
      $sql = "INSERT INTO `forumpost` (`postID`, `postedBy`, `postedTo`, `postContent`, `postedOn`, `postedAt`) VALUES (NULL, :thisUser, :thisJC, :content, :ddate, :ttime);";
      $statemnt = $pdo->prepare($sql);
      $statemnt->execute(array(
        ':thisUser' => $_SESSION['user'],
        ':thisJC' => $_SESSION['JobcardID'],
        ':content' => $_POST['searchbar'],
        ':ddate' => date('Y-m-d'),
        ':ttime' => date("H:i:s"),
      ));
    if($_SESSION['type'] == "mechanic"){ //updates the unseen status after the post has been made
      $sql = "UPDATE `jobcard` SET `updatesForManic` = '1' WHERE `jobcard`.`cardID` = :JC;";
      $statemnt = $pdo->prepare($sql);
      $statemnt->execute(array(
      ':JC' => $_SESSION['JobcardID'],
      ));
      header("Location: jobcardMechanic.php");
      return;
    }else{ //updates the unseen status after the post has been made
      $sql = "UPDATE `jobcard` SET `updatesForMechanic` = '1' WHERE `jobcard`.`cardID` = :JC;";
      $statemnt = $pdo->prepare($sql);
      $statemnt->execute(array(
        ':JC' => $_SESSION['JobcardID'],
      ));
      header("Location: jobcardMechanic.php");
      return;
    }
  }
?>

<html>
<head>
  <link type="text/css" rel="stylesheet" href="jobcardMechanic.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
  <script src = http://code.jquery.com/jquery-3.3.1.js>
  </script>
  <script type="text/javascript" src="jquery.min.js"></script>
  <script defer src = "jobcard.js"></script>
  <title> Jobcard </title>
  <script>
    function logout(){
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
        <h1 style="text-align: center;">Jobcard</h1>
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
  <div class = "forumArea" >
  <span id = "postContentArea">
    <br>
    <?php //This php script populates the forum area upon the page's first load
      $sqlRead = "select * FROM forumpost where postedTo = :JC;";
      $statement1 = $pdo->prepare(
        $sqlRead
      );
      $statement1->execute(array(     
      ':JC' => $_SESSION['JobcardID'], 
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
          if($resultRow['postedBy']==$_SESSION['user']){
            echo("<div class = \"aPost\" style = \"background-color: #bfedff;\">
      <table>
        <tr><td  style=\"width:75%;\">Posted By:  <strong>".htmlentities($resultRoww['firstName']) ." ".htmlentities($resultRoww['secondName']) ."</strong></td><td style = \"width:25%\">&nbsp;</td></tr>
        <tr><td  style=\"width:75%;\">Date:  <strong>".$resultRow['postedOn']."</strong><br>Time:  <strong>".htmlentities($resultRow['postedAt'])."</strong></td><td style = \"width:25%\">&nbsp;</td></tr>
        <tr><td colspan = \"2\" ><p style = \"overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; \">".htmlentities($resultRow['postContent'])."</p></td></tr>
      </table>
    </div><br>");
          }else{
            echo("<div class = \"aPost\" style = \"background-color: #ddffd1;\">
      <table>
        <tr><td  style=\"width:75%;\">Posted By:  <strong>".htmlentities($resultRoww['firstName']) ." ".htmlentities($resultRoww['secondName']) ."</strong></td><td style = \"width:25%\">&nbsp;</td></tr>
        <tr><td  style=\" width:75%;\">Date:  <strong>".$resultRow['postedOn']."</strong><br>Time:  <strong>".htmlentities($resultRow['postedAt'])."</strong></td><td style = \"width:25%\">&nbsp;</td></tr>
        <tr><td colspan = \"2\" ><p style = \"overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; \">".htmlentities($resultRow['postContent'])."</p></td></tr>
      </table>
    </div><br>");
          }
        }
      }
    ?>
    </span>
    <script type="text/javascript">
      function postBottom(){ //this function will ensure that the post area is focused on the newest post
        var forumAreaa = document.querySelector('.postContentArea');
        postContentArea.scrollTop = postContentArea.scrollHeight;
      }
    </script>
    <script>
      $(document).ready(function(){
        $.ajaxSetup({cache:false});
        postBottom();
        checkForUnseenJobcardPost();
      });

      function checkForUnseenJobcardPost() { //This funciton will be called once the page has loaded and then call itself in a recurring fashion each 2 seconds
        $.getJSON('jobcardAutoUpdate.php', function(rowz){
          window.console && console.log('JSON CheckValue Received'); 
          iterator = 0;
          while(iterator < rowz.length){
            arow = rowz[iterator];
            if(arow['updatesForMechanic'] == 1 && arow['cardID'] == <?php echo($_SESSION['JobcardID']);?>){
              clearedAndUpdatePostArea();
            }
            iterator++;
          }
          setTimeout('checkForUnseenJobcardPost()', 2000);
        });
      }
      function clearedAndUpdatePostArea(){ //This function will first be called by the 'checkForUnseenJobcardPost' function in the case of an unseen update having occured
        $.getJSON('RetrievePosts.php?CardID='+<?php echo($_SESSION['JobcardID']);?>, function(rowz){
        document.getElementById("postContentArea").innerHTML = "";
          for (var i = 0; i < rowz.length; i++) {
            arow = rowz[i];
            postedBy = arow['postedBy'];
            firstName = arow['firstName'] ;
            secondName = arow['secondName'];
            postedAt = arow['postedAt'];
            postedOn = arow['postedOn'];
            postContent = arow['postContent'];
            displayUpdatedPostArea();
          }
          postBottom();
          mechanicSeenPosts();
        });
      }
      function mechanicSeenPosts(){ //This function will be called by the 'clearedAndUpdatePostArea()' function in order to reflect that the update has been seen
        $.getJSON("mechanicSeenPosts.php?jc="+<?php echo($_SESSION['JobcardID']);?>, function(rowz){
        });
      }
      function displayUpdatedPostArea(){ //This function will be called by the 'clearedAndUpdatePostArea()' function in order to update the post area's content
      written = '';
      if(postedBy == <?php echo($_SESSION['user'])?>){
        written = written + '<div class = "aPost" style = "background-color: #bfedff;"><table><tr><td  style="width:75%;">Posted By:  <strong>'+firstName+' '+secondName+'</strong></td><td style = "width:25%">&nbsp;</td></tr><tr><td  style="width:75%;">Date:  <strong>'+postedOn+'</strong><br>Time:  <strong>'+postedAt+'</strong></td><td style = "width:25%">&nbsp;</td></tr><tr><td colspan = "2" ><p style = "overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; ">'+postContent+'</p></td></tr></table></div><br>'
      }else{
        written = written + '<div class = "aPost" style = "background-color: #ddffd1;"><table><tr><td  style="width:75%;">Posted By:  <strong>'+firstName+' '+secondName+'</strong></td><td style = "width:25%">&nbsp;</td></tr><tr><td  style="width:75%;">Date:  <strong>'+postedOn+'</strong><br>Time:  <strong>'+postedAt+'</strong></td><td style = "width:25%">&nbsp;</td></tr><tr><td colspan = "2" ><p style = "overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; ">'+postContent+'</p></td></tr></table></div><br>'
      }
      document.getElementById("postContentArea").innerHTML = document.getElementById("postContentArea").innerHTML + written;
    }
  </script>
  <span  class = "newPost">
    <form action = "jobcardMechanic.php" method = "POST">
      <table style = "table-layout: fixed; width: 100%;height:100%; text-align: center;">
        <tr>
            <td  style = "width:80%;">
              <textarea style = "width:90%;height:70%; resize: none;" id = "searchbar" name = "searchbar" placeholder = "New Post Content...."> </textarea>
            </td>
            <td>
                <button id = "submitpost" type = "submit" style = "border-radius:10px; background-color: #bfedff; width:100%; height:70%;">Post</button>
            </td  style = "width:20%;">
        </tr>
      </table>
    </form>
  </span>
  </div>
  <?php //This script will populate the jobcard's informaiton at the top of the page 
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
      $_SESSION['bikesID'] = $resultRow['bikeID'];
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
          echo(htmlentities($resultRow3['clientFullname']));
          echo("</td><td style = \"width:20%;\"><strong>Date:</strong></td><td colspan=\"2\">");
          echo($resultRow['dateCreated']);
          echo("</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Mechanic:</strong></td><td colspan=\"2\">");
          $sqluser = "select * FROM user WHERE userID= :uID;";
          $innerstatementuser = $pdo->prepare(
            $sqluser
          );
          $_SESSION['mechanicsID'] = $resultRow['mechanicAssigned'];
          $innerstatementuser->execute(array(    
            ':uID' => $resultRow['mechanicAssigned'], 
          ));
          while( $resultRoww = $innerstatementuser->fetch(PDO::FETCH_ASSOC)){
            echo(htmlentities($resultRoww['firstName']). " ". htmlentities($resultRoww['secondName']));
            echo("</td><td style = \"width:20%;\"><strong>Status:</strong></td><td colspan=\"2\">");
            if($resultRow['completed'] == 0){
              echo("In progress");
            }else{
              echo("Completed");
            }
            echo("</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Job Number:</strong></td><td>");
            echo($resultRow['jobNumber']);
            $_SESSION['quotesID'] = $resultRow['quoteID'];
            echo("</td><td style = \"width:20%;\"><strong>Admin:</strong></td><td colspan=\"1\">");
            echo(htmlentities($resultRow['admin']));
            echo("</td><td style = \"width:20%;\"><strong>Booked By:</strong></td><td colspan=\"1\">");
            echo(htmlentities($resultRow['bookedBy']));
            echo("</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Bike Detail:</strong></td><td colspan=\"5\">");
            echo(htmlentities($resultRow['briefBikeDescription']));
          }
        }
      }
    }
  ?></td></tr></table></center></div>
  <div class = "taskArea" > 
    <?php  //This php script will populate the task area in order to show the totals of the tasks associated to the jobcard
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
        <td>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</td>
        <td>Total Mechanic Compensation:</td>
        <td><?php echo("R".$mechanicTotal);?></td>
      </tr>
    </table>
  </div>
  <br>
  <?php //This php script will populate the task area associated to the jobcard
    $sql = "select * FROM task where cardID = :cID;";
    $statement = $pdo->prepare(
      $sql
    );
    $statement->execute(array(     
      ':cID' => $_SESSION['JobcardID'], 
    ));
    $taskCount = 1;
    while( $resultRow = $statement->fetch(PDO::FETCH_ASSOC)){
      echo("<div style = \"background-color: #DEFFD9;\" id = \"task");
      echo($resultRow['taskID']);
      echo("\">"); 
      echo("
 <table style = \"table-layout: fixed; width: 100%; text-align: center; border-collapse: collapse;border: 3px solid black;\">
  <th colspan=\"4\" >Task <span id=\"".$resultRow['taskID']."\">".$taskCount."</th>
    <tr style = \" border: 1px solid black;\">
        <td style = \"width:20%;\"><strong>Stock Used <br>/ Labour Charge:</strong></td><td>". htmlentities($resultRow['stockUsedAndLabourCharge'])."</td>
        <td style = \"width:20%;\"><strong>Quantity:</strong></td><td>".$resultRow['quantity']."</td></tr>
    <tr style = \" border: 1px solid black;\">
        <td style = \"width:20%;\"><strong>Mechanic Compensation:</strong></td><td>".$resultRow['mechanicCompensation']."</td><td style = \"width:20%;\"><strong>Subtotal:</strong></td><td>".$resultRow['mechanicCompensation']*$resultRow['quantity']."</td></tr>
    <tr style = \" border: 1px solid black;\">
        <td style = \"width:20%;\"><strong>Code:</strong></td><td>".htmlentities($resultRow['code'])."</td>
        <td style = \"width:20%;\"><strong>Status:</strong></td><td>");
      if($resultRow['completed'] == "0"){
        echo("In Progress");
      }else{
        echo("Completed");
      }
      echo("</td>
        </tr>
        
        <tr>
          <td colspan = \"4\"><center>
        <form action=\"jobcardMechanic.php\" method = \"POST\">
          <button class = \"taskbutton\" value = \"".$resultRow['taskID']."\" name = \"ToggleCompl\" type = \"submit\" style = \"border-radius:10px; width:50%; height:100%;\">Toggle Completion Status</button></form></center>");
      echo("     
        </td> 
            </tr>
       </form>
</table>");
      $taskCount++;
      echo("
        </div><br>");
    }
  ?></div>
</body>
</html>