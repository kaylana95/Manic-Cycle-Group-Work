<?php
    session_start();

    //validate user login
    if(isset($_SESSION['type'])){
        if($_SESSION['type'] != "administrator" && $_SESSION['type'] != "manager"){
            header('Location: ../serviceManagement/servicemanagerMechanic.php');
            return;
        }
    }else{
        header('Location: ../index.php');
        return;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="assets/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap.min.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="img/logo.png">
    <title>Quote Management</title>

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

        function viewQuote($quoteID){
            window.location.href="quoteServicesView.php?quoteID="+$quoteID;
        }

        function deleteQuote($quoteID){
            window.location.href="deleteQuoteController.php?quoteID="+$quoteID;
        }
    </script>
</head>

<header>
    <div class="container-fluid">
        <div class="row p-2">
            <div class="col-sm-4">
                <a href=""><img src="img/logo.png"  id="logo"></a>
            </div>

            <div class="col-sm-4">
                <h1 style="text-align: center;">Quote Management</h1>
            </div>
        </div>
    </div>

    <div id="searchBar" class="container-fluid mt-2'">
        <div class="row p-2">
            <div class="col-sm-12">
                <span class="mx-auto">
                    <form id="search_form" class="form-inline" action="quoteManagement.php" method="get">
                        <input type="text" style="height: 60px; width: 80%;" class="form-control" name="searchCrit" id="searchCrit" placeholder="Search Quote">
                        <button id="searchBtn" type="submit" class="btn"><i class="fas fa-search"></i></button>
                    </form>
                </span>
            </div>
        </div>
    </div>
</header>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <span class="buttonSpan">
                    <button data-toggle="modal" data-target="#addQuoteModal" id="addQuoteServicesModalBtn">Create New Quote</button>   

                </span>
                <div class="modal fade" id="addQuoteModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title">Add Quote</h1>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <form action="addQuote.php" method="post">
                                <div class="modal-body">
                                    <fieldset>
                                        <legend>Quote Details</legend>

                                        <div class="form-group">
                                            <label for="purDate">Date of Issue</label>
                                            <input type="date" class="form-control" id="purDate" name="purDate">
                                        </div>
                                        <div class="form-group" id="sr">
                                            <label for="serialNumber">Time of Issue:</label>
                                            <input type="time" class="form-control" id="serialNumber" name="serialNumber">

                                        </div>

                                        <div class="form-group">
                                            <label for="clientID">Client ID:</label>
                                            <select id="clientFullName" class="form-control" name="clientFullName">
                                                <?php 
                                                    $con = mysqli_connect("localhost", "root", "");
                                                    mysqli_select_db($con, "servicemanagement");

                                                    $select_clients_result = mysqli_query($con, "SELECT `clientFullname` FROM `client` ORDER BY `clientFullname` ASC");
                                                    
                                                    if(mysqli_num_rows($select_clients_result) > 0){
                                                        while($row = mysqli_fetch_assoc($select_clients_result)){
                                                            ?>
                                                                <option><?php echo $row['clientFullname']; ?></option>
                                                            <?php
                                                        }
                                                    }

                                                ?>
                                            </select>
                                        </div>
                                        
                                    </fieldset>
                                </div>

                                <div class="modal-footer">
                                    <div class="form-group float-right">
                                        <input type="submit" class="btn btn-success" value="Save Quote">
                                        <input type="reset" class="btn btn-danger" value="Cancel" data-dismiss="modal">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div id="quoteServices_container" class="container">
            <div class="row">
                <div class="col-sm-12">
                    <table style="text-align: center;">
                        <th>Quote ID</th>
                        <th>Client</th>
                        <th>Date Produced</th>
                        <th>Total Price</th>
                        
                        <?php 
                            function str_search($needle, $haystack_str){
                                $needle_arr = explode("", $needle);
                                $haystack_arr = explode("", $haystack_str);
                                
                                foreach($haystack_arr as $char){
                                    foreach($needle_arr as $needle_char){
                                        if($char == $needle_char){
                                            return true;
                                        }
                                    }
                                }

                                return false;
                            }

                            $con = mysqli_connect("localhost", "root", "");
                            mysqli_select_db($con, "servicemanagement");

                            $results = mysqli_query($con, "SELECT * FROM `quote`");

                            if(!isset($_GET['searchCrit']) || $_GET['searchCrit'] == ""){
                                if(mysqli_num_rows($results) > 0){
                                    while($row = mysqli_fetch_assoc($results)){
                                        ?>
                                            <tr>
                                                <td><?php echo $row['quoteID']; ?></td>
                                                <td>
                                                    <?php 
                                                        require_once '../bicycleManagement/Bicycle.php';
    
                                                        $bikeObj = new Bicycle();
                                                        echo $bikeObj->getClientName($row['clientID']);
                                                    ?>
                                                </td>
                                                <td><?php echo $row['producedOn'];?></td>
                                                <td>R <?php echo $row['totalEstimatedPrice'];?></td>
                                                <td>
                                                    <button onclick="viewQuote(<?php echo $row['quoteID']; ?>)" id="addQuoteModalBtn" style="width: 100px;">View</button>
                                                    <button onclick="deleteQuote(<?php echo $row['quoteID']; ?>)" id="addQuoteModalBtn" style="width: 100px;">Delete</button>
                                                </td>
                                            </tr>
                                        <?php
                                    }
                                }else{
                                    ?>
                                        <tr><td colspan="4"><h3>No Quotes Found</h3></td></tr>
                                    <?php
                                }
                            }else{
                                if(mysqli_num_rows($results) > 0){
                                    while($row = mysqli_fetch_assoc($results)){
                                        $search_string = implode(" ", $row);

                                        if(str_search($_GET['searchCrit'], $search_string)){
                                            ?>
                                                <tr>
                                                    <td><?php echo $row['quoteID']; ?></td>
                                                    <td>
                                                        <?php 
                                                            require_once '../bicycleManagement/Bicycle.php';
        
                                                            $bikeObj = new Bicycle();
                                                            echo $bikeObj->getClientName($row['clientID']);
                                                        ?>
                                                    </td>
                                                    <td><?php echo $row['producedOn'];?></td>
                                                    <td>R <?php echo $row['totalEstimatedPrice'];?></td>
                                                    <td>
                                                        <button onclick="viewQuote(<?php echo $row['quoteID']; ?>)" id="addQuoteModalBtn" style="width: 100px;">View</button>
                                                        <button onclick="deleteQuote(<?php echo $row['quoteID']; ?>)" id="addQuoteModalBtn" style="width: 100px;">Delete</button>
                                                    </td>
                                                </tr>
                                            <?php
                                        }
                                    }
                                }else{
                                    ?>
                                        <tr><td colspan="4"><h3>No Quotes Found</h3></td></tr>
                                    <?php
                                }
                            }
                        ?>
                       
                    </table>
                </div>
            </div>
        </div>
                                                     
                                                           
                                                        
   <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="assets/js/dataTables.bootstrap.min.js"></script>
      



</body>
    <footer>
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
                        <a style="color: #f1ff87;" href="../serviceManagement/servicemanager.php" class="nav-link"><h4>Services</h4></a>
                    </li>
                    
                    <li class="nav-item">
                        <a style="color: #f1ff87;" href="../stockManagement/" class="nav-link"><h4>Stock</h4></a>
                    </li>

                    <li>
                        <a style="color: #3b3b3b;" href="quoteManagement.php" class="nav-link active"><h4>Quote</h4></a>
                    </li>

                    <li>
                        <a style="color: #f1ff87;" href="../BicycleManagement/BicycleManagementView.php" class="nav-link"><h4>Bicycle</h4></a>
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
    </footer>

</html>
