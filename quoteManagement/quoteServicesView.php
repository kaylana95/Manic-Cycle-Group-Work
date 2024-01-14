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

        function deleteQuoteService(quoteServiceID, quoteID, estimatedPrice){
            window.location.href="deleteQuoteServiceController.php?quoteServiceID="+quoteServiceID + "&quoteID=" + quoteID + "&estimatedPrice=" + estimatedPrice;
        }
    </script>
</head>

<header>
    <div class="container-fluid">
        <div class="row p-2">
            <div class="col-sm-4">
                <a href=""><img src="img/logo.png" alt="companylogo" id="logo"></a>
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
        <div>
            <button style="margin-left: 40%;" data-toggle="modal" data-target="#addQuoteServicesModal" id="addQuoteModalBtn">Create Quote Services</button>

            <div class="modal fade" id="addQuoteServicesModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title">Quote Services</h1>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form action="addQuoteServices.php" method="post">
                            <div class="modal-body">
                                <fieldset>
                                    <legend>Quote Services Information</legend>
                                    <input type="text" id="quoteID" name="quoteID" value="<?php echo $_GET['quoteID']; ?>" hidden>

                                    <div class="form-group">
                                        <label for="lN">Service Description:</label>
                                        <textarea id="comments" class="form-control" name="comments" rows="10"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="eP">Estimated Price:</label>
                                        <input type="number" class="form-control" id="eP" name="eP" placeholder="Enter Amount">

                                    </div>

                                    <div class="modal-footer">
                                        <div class="form-group float-right">
                                            <button type="submit" float-right class="btn btn-primary">Save
                                                <span class="glyphicon glyphicon-saved" aria-hidden="true"></span>
                                            </button>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="quoteServices_container" class="container">
            <div class="row">
                <div class="col-sm-12">
                    
                    <table style="text-align: center;">
                        <tr style="text-align: end;">
                            <td colspan="4">
                                <h2>
                                    Quote Number: <?php echo $_GET['quoteID']; ?><br> 
                                    Total Amount: R 
                                    
                                    <?php  
                                        require '../connect_db.php';
                                        $total_amount_result = mysqli_query($con, "SELECT `totalEstimatedPrice` FROM `quote` WHERE `quoteID`='" . $_GET['quoteID'] ."'");
                                        $results_arr = mysqli_fetch_assoc($total_amount_result);
                                        echo ($total_amount_result != FALSE ? $results_arr['totalEstimatedPrice'] : "ERRR: Total Not Found");
                                    ?>
                                
                                </h2>
                            </td>
                        </tr>
                        <th>Quote Services ID</th>
                        <th>Service Description</th>
                        <th>Estimated Price</th>
                        <th>Check to <br> delete</th>
                        <?php 
                            require '../connect_db.php';

                            $result = mysqli_query($con, "SELECT * FROM `quoteservices` WHERE `quoteID`='". $_GET['quoteID'] ."'");
                            
                            if(mysqli_num_rows($result) > 0){
                                while($row = mysqli_fetch_assoc($result)){
                                    ?>
                                        <tr>
                                            <td><?php echo $row['quoteServiceID']; ?></td>
                                            <td><?php echo $row['serviceDescription']; ?></td>
                                            <td>R <?php echo $row['estimatedPrice']; ?></td>
                                            <td><input onclick="deleteQuoteService(<?php echo $row['quoteServiceID']; ?>, <?php echo $row['quoteID']; ?>, <?php echo $row['estimatedPrice']; ?>)" type="checkbox"></td>
                                        </tr>
                                    <?php
                                }
                            }else{
                                echo "<tr><td colspan=\"4\"><h3>No services found</h3></td></tr>";
                            }

                        ?>
                       
                    </table>
                </div>
            </div>
        </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

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