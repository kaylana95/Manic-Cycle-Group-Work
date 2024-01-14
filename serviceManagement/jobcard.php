<?php session_start();

if(isset($_SESSION['type'])){ //ensures valid logged in user
	if($_SESSION['type'] != "administrator" && $_SESSION['type'] != "manager"){
    	header('Location: ../serviceManagement/servicemanagerMechanic.php');
          return;
      }
  }else{
      header('Location: ../index.php');
      return;
  }
require_once "manicPDO.php";
if(isset($_POST['PDF'])){
  	header("Location: jobcardPDF.php");
    return;
}

if(isset($_POST['taskID'])) { // handles delete operation of tasks
    $sql = "DELETE FROM `task` WHERE `task`.`taskID` = :del;";
    $statemnt = $pdo->prepare($sql);
    $statemnt->execute(array(
    ':del' => $_POST['taskID'],
    ));
    header("Location: jobcard.php");
    return;
}

if(isset($_POST['taskNum'])){ //handles update operation of tasks
	$sql = "UPDATE `task` SET `stockUsedAndLabourCharge` = :sAndl, `quantity` = :q, `costToCustomer` = :cTo, `mechanicCompensation` = :mC, `code` = :c WHERE `task`.`taskID` = :tID;";
    $statemnt = $pdo->prepare($sql);
    $statemnt->execute(array(
    ':sAndl' => $_POST['tupStockAndLabour'],
    ':q' => $_POST['tupQuantity'],
    ':cTo' => $_POST['cToc'],
    ':mC' => $_POST['tupMComp'],
    ':c' => $_POST['tupCode'],
    ':tID' => $_POST['taskNum'],
    ));
    header("Location: jobcard.php");
    return;
}

if(isset($_POST['updateBookedBy'])){ // handles update operation of jobcard
	if($_POST['completionstatus'] == 0){ //checks completed status of job card
		$sql = "UPDATE `jobcard` SET `quoteID` = :qID, `jobNumber` = :jN, `invoiceNumber` = :iNv, `mechanicAssigned` = :mA, `bikeID` = :bID, `bookedBy` = :bB, `briefBikeDescription` = :bBD, `completed` = :cS, `admin` = :a WHERE `jobcard`.`cardID` = :jc;";
	     $statemnt = $pdo->prepare($sql);    
	    if($_POST['updateQuoteID'] == ""){ //checks null for quote
		    $statemnt->execute(array(
		    ':jc' => $_SESSION['JobcardID'],
		    ':qID' => null,
		    ':jN' => $_POST['updateJobNumber'],
		    ':iNv' => $_POST['updateInvoiceNumber'],
		    ':mA' => $_POST['updateMechanicID'],
		    ':bID' => $_POST['updateBikeID'],
		    ':bB' => $_POST['updateBookedBy'],
		    ':bBD' => $_POST['updateBikeDesc'],
		    ':cS' => 0,
		    ':a' => $_POST['updateAdmin'],
		    ));
		    header("Location: jobcard.php");
		    return;
	    }else{//checks null for quote
			$statemnt->execute(array(
			':jc' => $_SESSION['JobcardID'],
		    ':qID' => $_POST['updateQuoteID'],
		    ':jN' => $_POST['updateJobNumber'],
		    ':iNv' => $_POST['updateInvoiceNumber'],
		    ':mA' => $_POST['updateMechanicID'],
		    ':bID' => $_POST['updateBikeID'],
		    ':bB' => $_POST['updateBookedBy'],
		    ':bBD' => $_POST['updateBikeDesc'],
		    ':cS' => 0,
		    ':a' => $_POST['updateAdmin'],
		    ));
		    header("Location: jobcard.php");
		    return;
	    }    
	}else{ //checks completed status of job card
		$sql = "UPDATE `jobcard` SET `quoteID` = :qID, `jobNumber` = :jN, `invoiceNumber` = :iNv, `mechanicAssigned` = :mA, `bikeID` = :bID, `bookedBy` = :bB, `briefBikeDescription` = :bBD, `completed` = :cS, `admin` = :a WHERE `jobcard`.`cardID` = :jc;";
	                $statemnt = $pdo->prepare($sql);
	    if($_POST['updateQuoteID'] == ""){ //checks null for quote
		    $statemnt->execute(array(
		    ':jc' => $_SESSION['JobcardID'],
		    ':qID' => null,
		    ':jN' => $_POST['updateJobNumber'],
		    ':iNv' => $_POST['updateInvoiceNumber'],
		    ':mA' => $_POST['updateMechanicID'],
		    ':bID' => $_POST['updateBikeID'],
		    ':bB' => $_POST['updateBookedBy'],
		    ':bBD' => $_POST['updateBikeDesc'],
		    ':cS' => 1,
		    ':a' => $_POST['updateAdmin'],
		    ));
		    header("Location: jobcard.php");
		    return;
	    }else{ //checks null for quote
			$statemnt->execute(array(
			':jc' => $_SESSION['JobcardID'],
		    ':qID' => $_POST['updateQuoteID'],
		    ':jN' => $_POST['updateJobNumber'],
		    ':iNv' => $_POST['updateInvoiceNumber'],
		    ':mA' => $_POST['updateMechanicID'],
		    ':bID' => $_POST['updateBikeID'],
		    ':bB' => $_POST['updateBookedBy'],
		    ':bBD' => $_POST['updateBikeDesc'],
		    ':cS' => 1,
		    ':a' => $_POST['updateAdmin'],
		    ));
		    header("Location: jobcard.php");
		    return;
	    }
	}
}

if(isset($_POST['newTask'])){ //handles new task creation operation
$sql = "INSERT INTO `task` (`taskID`, `cardID`, `stockUsedAndLabourCharge`, `quantity`, `costToCustomer`, `quantitativeTotalCostToCustomer`, `mechanicCompensation`, `quantitativeTotalMechanicCompensation`, `code`, `completed`) VALUES (NULL, :jc, NULL, NULL, NULL, NULL, NULL, NULL,NULL, :comp);";
    $statemnt = $pdo->prepare($sql);
    $statemnt->execute(array(
    ':jc' => $_SESSION['JobcardID'],
    ':comp' => 0,
    ));
    header("Location: jobcard.php");
    return;
}

if($_SESSION['type'] == "mechanic"){ //handles the "updates seen" on service management page
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


if(isset($_POST['searchbar'])){ //This hangles a new post having been made to the job card
	$sql = "INSERT INTO `forumpost` (`postID`, `postedBy`, `postedTo`, `postContent`, `postedOn`, `postedAt`) VALUES (NULL, :thisUser, :thisJC, :content, :ddate, :ttime);";
    $statemnt = $pdo->prepare($sql);
    $statemnt->execute(array(
    ':thisUser' => $_SESSION['user'],
    ':thisJC' => $_SESSION['JobcardID'],
    ':content' => $_POST['searchbar'],
    ':ddate' => date('Y-m-d'),
    ':ttime' => date("H:i:s"),
    ));
    if($_SESSION['type'] == "mechanic"){ //Updates the "unseen updates" on the service manager page accordingly
    	$sql = "UPDATE `jobcard` SET `updatesForManic` = '1' WHERE `jobcard`.`cardID` = :JC;";
        $statemnt = $pdo->prepare($sql);
        $statemnt->execute(array(
        ':JC' => $_SESSION['JobcardID'],
    	));
	    header("Location: jobcard.php");
    	return;
    }else{ //Updates the "unseen updates" on the service manager page accordingly
    	$sql = "UPDATE `jobcard` SET `updatesForMechanic` = '1' WHERE `jobcard`.`cardID` = :JC;";
        $statemnt = $pdo->prepare($sql);
        $statemnt->execute(array(
    	':JC' => $_SESSION['JobcardID'],
        ));
        header("Location: jobcard.php");
    	return;
	}
}
?>
<html>
<head>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<link type="text/css" rel="stylesheet" href="jobcard.css">
<script src = http://code.jquery.com/jquery-3.3.1.js></script>
<title> Jobcard </title>
<script>
    function logout(){ //Alert message for logout
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
                    <a style="color: #3b3b3b;" href="../serviceManagement/servicemanager.php" class="nav-link  active"><h4>Services</h4></a>
                </li>
                <li class="nav-item">
                    <a style="color: #f1ff87;" href="../stockManagement/Home.php" class="nav-link"><h4>Stock</h4></a>
                </li>
                <li class="nav-item">
	                <a style="color: #f1ff87;" href="../BicycleManagement/BicycleManagementView.php" class="nav-link"><h4>Bicycles</h4></a>
                </li>
                <li class="nav-item">
                    <a style="color: #f1ff87;" href="../quoteManagement/quoteManagement.php" class="nav-link"><h4>Quote</h4></a>
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
	<form action="jobcard.php" method = "POST">
    	<input type="submit" class ="navButton" value="PDF" name = "PDF" style = "right:4%; top:1%;width: 10%;"/>
	</form>
	<div class = "forumArea" >
		<span id = "postContentArea">
  		<br> 
  		<?php //Populates the forumpost area on first load of page
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
      <tr><td  style=\"width:75%;\">Date:  <strong>".htmlentities($resultRow['postedOn'])."</strong><br>Time:  <strong>".htmlentities($resultRow['postedAt'])."</strong></td><td style = \"width:25%\">&nbsp;</td></tr>
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
		<span  class = "newPost">
			<form action = "jobcard.php" method = "POST">
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
	<?php //Populates the jobcard info area on page's first load
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
					echo("</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Client Phone Number:</strong></td><td colspan=\"2\">");
					echo($resultRow3['phoneNumber']);
					echo("</td><td style = \"width:20%;\"><strong>Mechanic:</strong></td><td colspan=\"2\">");
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
						echo("</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Client Email:</strong></td><td colspan=\"2\">");
						echo( htmlentities($resultRow3['emailAddress']));
						echo("</td><td style = \"width:20%;\"><strong>Status:</strong></td><td colspan=\"2\">");
						if($resultRow['completed'] == 0){
		    				echo("In progress");
						}else{
		    				echo("Completed");
						}
						echo("</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Job Number:</strong></td><td>");
						echo($resultRow['jobNumber']);
						$_SESSION['quotesID'] = $resultRow['quoteID'];
						echo("</td><td style = \"width:20%;\"><strong>Quote Number:</strong></td><td>");
						echo($resultRow['quoteID']);
						echo("</td><td style = \"width:20%;\"><strong>Invoice Number:</strong></td><td>");
						echo($resultRow['invoiceNumber']);
						echo("</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Admin:</strong></td><td colspan=\"2\">");
						echo(htmlentities($resultRow['admin']));
						echo("</td><td style = \"width:20%;\"><strong>Booked By:</strong></td><td colspan=\"2\">");
						echo(htmlentities($resultRow['bookedBy']));
						echo("</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Bike Detail:</strong></td><td colspan=\"5\">");
						echo(htmlentities($resultRow['briefBikeDescription']));
					}
				}
			}
		}
	?></td></tr></table><br>
	<?php
		echo('<button class = "editJB" style = "width: 35%; height: 40px;" onclick = "editJobcard('.$_SESSION['JobcardID'].')">Edit Jobcard Information</button>');
	?><br></center></div>
	<script>
		function editJobcard(JobcardID){ //This function will be called when the 'Edit Jobcard Informaiton' button is clicked
			$.getJSON('jobcardRetrieve.php', function(rowz){
				for (var i = 0; i < rowz.length; i++) {
					arow = rowz[i];
					if(arow['cardID'] == JobcardID){
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
			        	updateJobcard();
					}
				}
			});
		}
		function cancelJobcard(JobcardID){ //After 'updatejobcard' functionis called, this function will be called provided that the cancel button is pressed
			$.getJSON('jobcardRetrieve.php', function(rowz){
				for (var i = 0; i < rowz.length; i++) {
					arow = rowz[i];
					if(arow['cardID'] == JobcardID){
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
			        	showJobcard();
					}
				}
			});
		}
	</script>
	<script>
	function showJobcard(){ //This function will in turn be called by the 'cancelJobcard' funciton after the cancel button has been pressed
		if(completed == 1){
			document.getElementById("jobcardInfoArea").innerHTML ="<center><table style = \"table-layout: fixed; width: 100%; height:100%; text-align: center; border-collapse: collapse;border: 1px solid black;\"> <tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Client Name:</strong></td><td colspan=\"2\">" + clientFullname +"</td><td style = \"width:20%;\"><strong>Date:</strong></td><td colspan=\"2\">" + dateCreated + "</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Client Phone Number:</strong></td><td colspan=\"2\">" + clientPhoneNumber + "</td><td style = \"width:20%;\"><strong>Mechanic:</strong></td><td colspan=\"2\">" + mechanicFirstName + " "+ mechanicSecondName + "</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Client Email:</strong></td><td colspan=\"2\">"+clientEmail+"</td><td style = \"width:20%;\"><strong>Status:</strong></td><td colspan=\"2\">"+"Completed"+"</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Job Number:</strong></td><td>"+jobNumber+"</td><td style = \"width:20%;\"><strong>Quote Number:</strong></td><td>"+quoteID+"</td><td style = \"width:20%;\"><strong>Invoice Number:</strong></td><td>"+invoiceNumber+"</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Admin:</strong></td><td colspan=\"2\">"+admin+"</td><td style = \"width:20%;\"><strong>Booked By:</strong></td><td colspan=\"2\">"+bookedBy+"</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Bike Detail:</strong></td><td colspan=\"5\">"+briefBikeDescription+"</td></tr><table><br><button class = \"editJB\" style = \"width: 35%; height: 40px;\" onclick = \"editJobcard("+cardID+")\">Edit Jobcard Information</button><br></center>";	
		}else{
		document.getElementById("jobcardInfoArea").innerHTML ="<center><table style = \"table-layout: fixed; width: 100%; height:100%; text-align: center; border-collapse: collapse;border: 1px solid black;\"> <tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Client Name:</strong></td><td colspan=\"2\">" + clientFullname +"</td><td style = \"width:20%;\"><strong>Date:</strong></td><td colspan=\"2\">" + dateCreated + "</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Client Phone Number:</strong></td><td colspan=\"2\">" + clientPhoneNumber + "</td><td style = \"width:20%;\"><strong>Mechanic:</strong></td><td colspan=\"2\">" + mechanicFirstName + " "+ mechanicSecondName + "</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Client Email:</strong></td><td colspan=\"2\">"+clientEmail+"</td><td style = \"width:20%;\"><strong>Status:</strong></td><td colspan=\"2\">"+"In Progress"+"</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Job Number:</strong></td><td>"+jobNumber+"</td><td style = \"width:20%;\"><strong>Quote Number:</strong></td><td>"+quoteID+"</td><td style = \"width:20%;\"><strong>Invoice Number:</strong></td><td>"+invoiceNumber+"</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Admin:</strong></td><td colspan=\"2\">"+admin+"</td><td style = \"width:20%;\"><strong>Booked By:</strong></td><td colspan=\"2\">"+bookedBy+"</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Bike Detail:</strong></td><td colspan=\"5\">"+briefBikeDescription+"</td></tr><table><br><button class = \"editJB\" style = \"width: 35%; height: 40px;\"onclick = \"editJobcard("+cardID+")\">Edit Jobcard Information</button><br></center>";
		}
	}
	function validateUpdateJobcardInformation(){ //This function will validate whether or not valid information has been submitted for a jobcard's information update
		var validated = ""
		theUpdateJobNumber  = document.getElementById("updateJobNumber").value; //check length
		if(isNaN(theUpdateJobNumber) || theUpdateJobNumber  == "" || theUpdateJobNumber  == null){
			validated = validated + "\r\nThe specified Job Number should be Numeric"
		}else{
			if(theUpdateJobNumber <=0){
			validated = validated + "\r\nThe specified specified Job Number should be greater than Zero"
			}
		}
		theUpdateInvoiceNumber  = document.getElementById("updateInvoiceNumber").value;
		if(isNaN(theUpdateInvoiceNumber) || theUpdateInvoiceNumber  == "" || theUpdateInvoiceNumber  == null){
			validated = validated + "\r\nThe specified Invoice Number should be Numeric"
		}else{
			if(theUpdateInvoiceNumber <=0){
			validated = validated + "\r\nThe specified specified Invoice Number should be greater than Zero"
			}
		}
		theUpdateAdmin  = document.getElementById("updateAdmin").value;
		if(theUpdateAdmin  == "" || theUpdateAdmin  == null){
			validated = validated + "\r\n'Admin' should be specified"
		}
		theUpdateBookedBy  = document.getElementById("updateBookedBy").value;
		if(theUpdateBookedBy  == "" || theUpdateBookedBy  == null){
			validated = validated + "\r\n'Booked By' should be specified"
		}
		if(validated != ""){
			alert(validated);
			return false;
		}else{
			return true;
		}
	}
	function updateJobcard(){ //This funciton will in turn be called by the 'editJobcard' function after the 'Edit Jobcard Informaiton' button is clicked
		if(completed == 1){
			document.getElementById("jobcardInfoArea").innerHTML ="<form method = \"POST\" action = \"jobcard.php\" onSubmit=\"return validateUpdateJobcardInformation()\"><center><table style = \"table-layout: fixed; width: 100%; height:100%; text-align: center; border-collapse: collapse;border: 1px solid black;\"> <tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Bicycle ID:</strong></td><td colspan=\"2\">" + 
			<?php
  				$sql = "SELECT bicycleID FROM `bicycle`;";
				$statement = $pdo->prepare(
              	$sql
          		);
	            $statement->execute(array(
		        ));
		        echo('\'<select id = "updateBikeID" name = "updateBikeID" style = "width:80%;height:100%;">');
          		while ($resultRow = $statement->fetch(PDO::FETCH_ASSOC)) {
          			if($resultRow['bicycleID'] == $_SESSION['bikesID']){
			            echo('<option value="'.$resultRow['bicycleID'].'" selected>'.$resultRow['bicycleID'].'</option>');
          			}else{
			            echo('<option value="'.$resultRow['bicycleID'].'">'.$resultRow['bicycleID'].'</option>');
          			}
                }echo("</select>'");
        	?>
			 +"</td><td style = \"width:20%;\"><strong>Mechanic ID:</strong></td><td colspan=\"2\">" + 
			<?php
 				$sql = 'SELECT userID FROM `user` WHERE userType = "mechanic"';
				$statement = $pdo->prepare(
              	$sql
          		);
	 	        $statement->execute(array(
		        ));
		        echo('\'<select id = "updateMechanicID"  name = "updateMechanicID" style = "width:80%;height:100%;">');
          		while ($resultRow = $statement->fetch(PDO::FETCH_ASSOC)) {
          			if($resultRow['userID']== $_SESSION['mechanicsID']){
			            echo('<option value="'.$resultRow['userID'].'" selected>'.$resultRow['userID'].'</option>');
          			}else{
			            echo('<option value="'.$resultRow['userID'].'">'.$resultRow['userID'].'</option>');
          			}
                }
                echo("</select>'");
        	?>
			+ "</td></tr><tr style = \" border: 1px solid black;\"><td></td><td style = \"width:20%;\"colspan=\"2\"><strong>Status:</strong></td><td colspan=\"2\">"+'<select name="completionstatus"><option value="1" selected>Completed</option><option value="0" >In Progress</option></select>'+"</td><td></td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Job Number:</strong></td><td>"+'<input id = "updateJobNumber" type = "text" name = "updateJobNumber" value = "'+jobNumber+'" style = "width:80%;height:100%;">'+"</td><td style = \"width:20%;\"><strong>Quote Number:</strong></td><td>"+
			<?php
  				$sql = "SELECT quoteID FROM `quote`;";
				$statement = $pdo->prepare(
              	$sql
          		);
				$statement->execute(array(
		        ));
		        echo('\'<select id = "updateQuoteID" name = "updateQuoteID" style = "width:80%;height:100%;">');
          		echo('<option value="'.$resultRow['quoteID'].'" selected>'.$resultRow['quoteID'].'</option>');
          		while ($resultRow = $statement->fetch(PDO::FETCH_ASSOC)) {
          			if($resultRow['quoteID']== $_SESSION['quotesID']){
			            echo('<option value="'.$resultRow['quoteID'].'" selected>'.$resultRow['quoteID'].'</option>');
          			}else{
			            echo('<option value="'.$resultRow['quoteID'].'">'.$resultRow['quoteID'].'</option>');
          			}
                }
                echo("</select>'");
	        ?>
			+"</td><td style = \"width:20%;\"><strong>Invoice Number:</strong></td><td>"+'<input id = "updateInvoiceNumber" type = "text" name = "updateInvoiceNumber" value = "'+invoiceNumber+'" style = "width:80%;height:100%;">'+"</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Admin:</strong></td><td colspan=\"2\">"+'<input id = "updateAdmin" type = "text" name = "updateAdmin" value = "'+admin+'" style = "width:80%;height:100%;">'+"</td><td style = \"width:20%;\"><strong>Booked By:</strong></td><td colspan=\"2\">"+'<input id = "updateBookedBy" type = "text" name = "updateBookedBy" value = "'+bookedBy+'" style = "width:80%;height:100%;">'+"</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Bike Detail:</strong></td><td colspan=\"5\">"+'<input id = "updateBikeDesc" type = "text" name = "updateBikeDesc" value = "'+briefBikeDescription+'" style = "width:80%;height:100%;">'+"</td></tr></table></center><br><center><input type = \"submit\" value = \"Update Jobcard Information\"class = \"editJB\" style = \"width: 35%; height: 40px;\"></center></form><center><button class = \"editJB\" style = \"width: 35%; height: 40px;\" onclick = \"cancelJobcard("+cardID+")\">Cancel</button></center><br>";		
		}else{
			document.getElementById("jobcardInfoArea").innerHTML ="<form method = \"POST\" action = \"jobcard.php\" onSubmit=\"return validateUpdateJobcardInformation()\"><center><table style = \"table-layout: fixed; width: 100%; height:100%; text-align: center; border-collapse: collapse;border: 1px solid black;\"> <tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Bicycle ID:</strong></td><td colspan=\"2\">" + 
			<?php
  				$sql = "SELECT bicycleID FROM `bicycle`;";
				$statement = $pdo->prepare(
              	$sql
          		);
		        $statement->execute(array(
	          	));
          		echo('\'<select id = "updateBikeID" name = "updateBikeID" style = "width:80%;height:100%;">');
          		while ($resultRow = $statement->fetch(PDO::FETCH_ASSOC)) {
          			if($resultRow['bicycleID'] == $_SESSION['bikesID']){
			            echo('<option value="'.$resultRow['bicycleID'].'" selected>'.$resultRow['bicycleID'].'</option>');
          			}else{
			            echo('<option value="'.$resultRow['bicycleID'].'">'.$resultRow['bicycleID'].'</option>');
          			}
                }
                echo("</select>'");
        	?>
			+"</td><td style = \"width:20%;\"><strong>Mechanic ID:</strong></td><td colspan=\"2\">" + 
			<?php
 				$sql = 'SELECT userID FROM `user` WHERE userType = "mechanic"';
				$statement = $pdo->prepare(
              	$sql
          		);
		        $statement->execute(array(
		        ));
		        echo('\'<select id = "updateMechanicID"  name = "updateMechanicID" style = "width:80%;height:100%;">');
        		while ($resultRow = $statement->fetch(PDO::FETCH_ASSOC)) {
          			if($resultRow['userID']== $_SESSION['mechanicsID']){
		 	           echo('<option value="'.$resultRow['userID'].'" selected>'.$resultRow['userID'].'</option>');
          			}else{
			            echo('<option value="'.$resultRow['userID'].'">'.$resultRow['userID'].'</option>');
          			}
                }
                echo("</select>'");
        	?>
			+ "</td></tr><tr style = \" border: 1px solid black;\"><td></td><td style = \"width:20%;\"colspan=\"2\"><strong>Status:</strong></td><td colspan=\"2\">"+'<select name="completionstatus"><option value="1">Completed</option><option value="0" selected>In Progress</option></select>'+"</td><td></td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Job Number:</strong></td><td>"+'<input id = "updateJobNumber" type = "text" name = "updateJobNumber" value = "'+jobNumber+'" style = "width:80%;height:100%;">'+"</td><td style = \"width:20%;\"><strong>Quote Number:</strong></td><td>"+
			<?php
  				$sql = "SELECT quoteID FROM `quote`;";
				$statement = $pdo->prepare(
              	$sql
          		);
		        $statement->execute(array(
		        ));
		        echo('\'<select id = "updateQuoteID" name = "updateQuoteID" style = "width:80%;height:100%;">');
          		echo('<option value="'.$resultRow['quoteID'].'" selected>'.$resultRow['quoteID'].'</option>');
          		while ($resultRow = $statement->fetch(PDO::FETCH_ASSOC)) {
          			if($resultRow['quoteID']== $_SESSION['quotesID']){
			            echo('<option value="'.$resultRow['quoteID'].'" selected>'.$resultRow['quoteID'].'</option>');
          			}else{
			            echo('<option value="'.$resultRow['quoteID'].'">'.$resultRow['quoteID'].'</option>');
          			}
                }
                echo("</select>'");
        	?>
			+"</td><td style = \"width:20%;\"><strong>Invoice Number:</strong></td><td>"+'<input id = "updateInvoiceNumber" type = "text" name = "updateInvoiceNumber" value = "'+invoiceNumber+'" style = "width:80%;height:100%;">'+"</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Admin:</strong></td><td colspan=\"2\">"+'<input id = "updateAdmin" type = "text" name = "updateAdmin" value = "'+admin+'" style = "width:80%;height:100%;">'+"</td><td style = \"width:20%;\"><strong>Booked By:</strong></td><td colspan=\"2\">"+'<input id = "updateBookedBy" type = "text" name = "updateBookedBy" value = "'+bookedBy+'" style = "width:80%;height:100%;">'+"</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Bike Detail:</strong></td><td colspan=\"5\">"+'<input id = "updateBikeDesc" type = "text" name = "updateBikeDesc" value = "'+briefBikeDescription+'" style = "width:80%;height:100%;">'+"</td></tr></table></center><br><center><input type = \"submit\" value = \"Update Jobcard Information\"class = \"editJB\" style = \"width: 35%; height: 40px;\"></center></form><center><button class = \"editJB\" style = \"width: 35%; height: 40px;\" onclick = \"cancelJobcard("+cardID+")\">Cancel</button></center><br>";
		}
	}
	</script>
	<div class = "taskArea" >
		<?php //This script will ensure that valid task information (the totals associated to the tasks) is retrieved from the database upon the page's first load
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
	<?php //This script will ensure that valid task information is retrieved from the database upon the page's first load
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
			echo("<table style = \"table-layout: fixed; width: 100%; text-align: center; border-collapse: collapse;border: 3px solid black;\">
  <th colspan=\"4\" >Task <span id=\"".$resultRow['taskID']."\">".$taskCount."</th>
    <tr style = \" border: 1px solid black;\">
        <td style = \"width:20%;\"><strong>Stock Used <br>/ Labour Charge:</strong></td><td>". htmlentities($resultRow['stockUsedAndLabourCharge'])."</td>
        <td style = \"width:20%;\"><strong>Quantity:</strong></td><td>".$resultRow['quantity']."</td></tr>
    <tr style = \" border: 1px solid black;\">
        <td style = \"width:20%;\"><strong>Cost To Customer:</strong></td><td>".$resultRow['costToCustomer']."</td>
        <td style = \"width:20%;\"><strong>Subtotal:</strong></td><td>".$resultRow['costToCustomer'] * $resultRow['quantity']."</td></tr>
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
			echo("</td></tr><tr><td colspan=\"2\"><button class = \"taskbutton\" type = \"submit\" style = \"border-radius:10px;  width:50%; height:100%;\" onclick = \"editTask('");
			echo($resultRow['taskID']);
	        echo("')\">Edit Task Information</button></td><td colspan=\"2\" style=\"vertical-align: middle;\"><br><form action=\"jobcard.php\" method = \"Post\" style ='height:100%;' onsubmit=\"return confirm('You are about to delete a Task');\"><input type = \"hidden\" name = \"taskID\" value = \"".$resultRow['taskID']."\">
	          <input class = \"taskbutton\" type = \"submit\" name = \"Delete Task\" value = \"Delete Task\" style = \"border-radius:10px; width:50%; height:100%; text-align: center; vertical-align: middle;\"></td></tr></form></table>");
			$taskCount++;
			echo("</div><br>");
		}
	?>
	<script>
	function editTask(tasksID){ //This funciton will execute after a Edit Task Information button has been pressed
		$.getJSON('taskRetrieve.php', function(rowz){
			for (var i = 0; i < rowz.length; i++) {
				arow = rowz[i];
				if(arow['taskID'] == tasksID){
				  	stockandlabour = arow['stockUsedAndLabourCharge'] ;
				   	quantity = arow['quantity'] ;
				   	ctocustomer = arow['costToCustomer'];
				  	mcompens = arow['mechanicCompensation'] ;
				   	code =  arow['code'] ;
				   	taskID = tasksID;
				   	updateTask(tasksID);
				}
			}
		});
	}
	function updateTask(tasksID){ //This funciton will be called by the 'editTask' funciton after a Edit Task Information button has been pressed
		taskID = tasksID;
		taskC = document.getElementById(tasksID).innerHTML;
		document.getElementById("task"+tasksID).innerHTML ='<form action="jobcard.php" method = "POST" onSubmit="return validateUpdateTaskInformation()"><table style = "table-layout: fixed; width: 100%; text-align: center; border-collapse: collapse;border: 3px solid black;"><th colspan="4" >Task <span id = "'+tasksID+'">'+taskC+'</th><tr style = " border: 1px solid black;"> <td style = "width:20%;"><strong>Stock Used <br>/ Labour Charge:</strong><td  style = "width:80%;"> <input id = "tupStockAndLabour" type = "text" name = "tupStockAndLabour" value = "'+stockandlabour+'" style = "width:100%;height:100%;"></td></td><td style = "width:20%;"><strong>Quantity:</strong></td><td><input id = "tupQuantity" type = "text" name = "tupQuantity" value = "'+quantity+'" style = "width:50%;height:100%;"></td>    </tr><tr style = " border: 1px solid black;"><td colspan="2" style = "width:20%"><strong>Cost To Customer:</strong></td><td colspan="2"><input id = "cToc" type = "text" name = "cToc" value = "'+ctocustomer+'" style = "width:50%;height:100%;"></td></tr><tr style = " border: 1px solid black;"><td style = "width:20%;" colspan="2"><strong>Mechanic Compensation:</strong></td><td colspan="2"><input id = "tupMComp" type = "text" name = "tupMComp" value = "'+mcompens+'" style = "width:50%;height:100%;"></td></tr><tr style = " border: 1px solid black;"><td style = "width:20%;" colspan="2"><strong>Code:</strong></td><td colspan="2"><input id = "tupCode" type = "text" name = "tupCode" value = "'+code+'" style = "width:50%;height:100%;"></td></tr></table><br><center><input class = "taskbutton" type = "submit" style = "border-radius:10px; width:50%; height:5%;" value ="Update Task Information"><input type = "hidden" name = "taskNum" value = "'+taskID+'"></center></form><center><button class = "taskbutton" type = "submit" style = "border-radius:10px; width:50%; height:5%;" onclick = "cancelUpdateTask('+tasksID+')" >Cancel</button></center><br>';
	}
	function validateUpdateTaskInformation(){ //This funciton will ensure that valid task information is provided during an update of a task's information 
		var validated = ""
		stockusedlaboutc = document.getElementById("tupStockAndLabour").value;
		if(stockusedlaboutc == "" || stockusedlaboutc == null){
			validated = validated + "\r\nStock Used / Labour Charge should be specified"
		}
		quant = document.getElementById("tupQuantity").value;
		if(isNaN(quant) || quant == "" || quant == null){
			validated = validated + "\r\nThe specified Quantity should be Numeric"
		}else{
			if(quant<=0){
				validated = validated + "\r\nThe specified Quantity should be greater than Zero"
			}
		}
		costToCus = document.getElementById("cToc").value;
		if(isNaN(costToCus) || costToCus == "" || costToCus == null){
			validated = validated + "\r\nThe specified Cost To Customer should be Numeric"
		}else{
			if(costToCus<=0){
				validated = validated + "\r\nThe specified Cost To Customer should be greater than Zero"
			}
		}
		mechCom = document.getElementById("tupMComp").value;
		if(isNaN(mechCom) || mechCom  == "" || mechCom  == null){
			validated = validated + "\r\nThe specified Mechanic Compensation should be Numeric"
		}else{
			if(mechCom <=0){
				validated = validated + "\r\nThe specified Mechanic Compensation should be greater than Zero"
			}
		}
		taskCode = document.getElementById("tupCode").value;
		if(taskCode == "" || stockusedlaboutc == null || taskCode.length >9){
			validated = validated + "\r\nInvalid Task Code Entered"
		}
		if(validated != ""){
			alert(validated);
			return false;
		}else{
			return true;
		}
	}
	function cancelUpdateTask(tasksID){ //This function will be called after the cancel button has been pressed for an update to a task's information
		taskID = tasksID;
		$.getJSON('taskRetrieve.php', function(rowz){
			for (var i = 0; i < rowz.length; i++) {
				arow = rowz[i];
				if(arow['taskID'] == tasksID){
					stockandlabour = arow['stockUsedAndLabourCharge'] ;
					quantity = arow['quantity'] ;
					ctocustomer = arow['costToCustomer'];
					mcompens = arow['mechanicCompensation'] ;
					code =  arow['code'] ;
					completed = arow['completed'];
					cancelTask(taskID);
				}
			}
		});
	}
	function cancelTask(tasksID){ //this funciton will in turn be called by the 'cancelUpdateTask' function after the cancel button has been pressed for an update to a task's information
		taskID = tasksID;
		taskC = document.getElementById(tasksID).innerHTML;
		if(completed == 1){
			document.getElementById("task"+tasksID).innerHTML ="<table style = \"table-layout: fixed; width: 100%; text-align: center; border-collapse: collapse;border: 3px solid black;\"><th colspan=\"4\" >Task <span id = '"+tasksID+"''>"+taskC+"</th><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Stock Used <br>/ Labour Charge:</strong></td><td>"+stockandlabour+"</td><td style = \"width:20%;\"><strong>Quantity:</strong></td><td>"+quantity+"</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Cost To Customer:</strong></td><td>"+ctocustomer+"</td><td style = \"width:20%;\"><strong>Subtotal:</strong></td><td>"+ctocustomer * quantity+"</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Mechanic Compensation:</strong></td><td>"+mcompens+"</td><td style = \"width:20%;\"><strong>Subtotal:</strong></td><td>"+mcompens*quantity+"</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Code:</strong></td><td>"+code+"</td><td style = \"width:20%;\"><strong>Status:</strong></td><td>"+"Completed"+"</td></tr><tr><td colspan=\"2\"><button class = \"taskbutton\" type = \"submit\" style = \"border-radius:10px;  width:50%; height:100%;\" onclick = \"editTask('"+taskID+"')\">Edit Task Information</button></td><td colspan=\"2\" style = \"vertical-align: middle;\"><form action=\"jobcard.php\" method = \"Post\" style = \"height:100%;\" onsubmit=\"return confirm('You are about to delete a Task');\"><input type = \"hidden\" name = \"taskID\" value = \""+taskID+"\"><input class = \"taskbutton\" type = \"submit\" name = \"Delete Task\" value = \"Delete Task\" style = \"border-radius:10px;  width:50%; height:100%; center; vertical-align: middle;\"></form></td></tr></table>";
		}else{
			document.getElementById("task"+tasksID).innerHTML ="<table style = \"table-layout: fixed; width: 100%; text-align: center; border-collapse: collapse;border: 3px solid black;\"><th colspan=\"4\" >Task <span id = '"+tasksID+"''>"+taskC+"</th><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Stock Used <br>/ Labour Charge:</strong></td><td>"+stockandlabour+"</td><td style = \"width:20%;\"><strong>Quantity:</strong></td><td>"+quantity+"</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Cost To Customer:</strong></td><td>"+ctocustomer+"</td><td style = \"width:20%;\"><strong>Subtotal:</strong></td><td>"+ctocustomer * quantity+"</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Mechanic Compensation:</strong></td><td>"+mcompens+"</td><td style = \"width:20%;\"><strong>Subtotal:</strong></td><td>"+mcompens*quantity+"</td></tr><tr style = \" border: 1px solid black;\"><td style = \"width:20%;\"><strong>Code:</strong></td><td>"+code+"</td><td style = \"width:20%;\"><strong>Status:</strong></td><td>"+"In Progress" +"</td></tr><tr><td colspan=\"2\"><button class = \"taskbutton\" type = \"submit\" style = \"border-radius:10px; width:50%; height:100%;\" onclick = \"editTask('"+taskID+"')\">Edit Task Information</button></td><td colspan=\"2\" style = \"vertical-align: middle;\"><br><form action=\"jobcard.php\" method = \"Post\" style = \"height:100%;\" onsubmit=\"return confirm('You are about to delete a Task');\"><input type = \"hidden\" name = \"taskID\" value = \""+taskID+"\"><input class = \"taskbutton\" type = \"submit\" name = \"Delete Task\" value = \"Delete Task\" style = \"border-radius:10px;  width:50%; height:100%; center; vertical-align: middle;\"></form></td></tr></table>";
		}
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
			iterator = 0;
            while(iterator < rowz.length){
                arow = rowz[iterator];
                if(arow['updatesForManic'] == 1 && arow['cardID'] == <?php echo($_SESSION['JobcardID']);?>){
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
            manicSeenPosts();
		});
    }
    function manicSeenPosts(){ //This function will be called by the 'clearedAndUpdatePostArea()' function in order to reflect that the update has been seen
		$.getJSON("manicSeenPosts.php?jc="+<?php echo($_SESSION['JobcardID']);?>, function(rowz){
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
	<center>
		<form action="Jobcard.php" method = "POST">
    		<input type="submit" id ="newTask" name = "newTask" value="Create New Task" style = "width: 35%; height: 40px;" />
		</form>    
	</center>
	</div>
</body>
</html>