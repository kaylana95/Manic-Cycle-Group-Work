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
        <link rel="stylesheet" type="text/css" href="styles.css">

        <meta charset="utf-8">
        
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="icon" href="../logo.png">
        <title>Bicycle Management</title>

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

            function openBicycle(bicycleId){
                window.location.href = "specificBicycleInfoPage.php?bikeId=" + bicycleId;
            }
        </script>
    </head>

    <header>
        <div class="container-fluid">
            <div class="row p-2">
                <div class="col-sm-4">
                    <a href=""><img src="../logo.png" alt="company_logo" id="logoImg"></a>
                </div>

                <div class="col-sm-4">
                    <h1 style="text-align: center;">Bicycle Management</h1>
                </div>

                <div class="col-sm-4">
                    
                </div>
                    
            </div>
 
        </div>

        <div id="top_search_bar" class="container-fluid mt-2">
            <div class="row p-2">
                <div class="col-sm-12">
                    <span class="mx-auto">
                        <form id="search_form" class="form-inline" action="bicycleManagementView.php" method="get">
                            <input type="text" style="height: 60px; width: 80%;" class="form-control" name="searchCrit" id="searchCrit" placeholder="Search Bicycles...">
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
                    <button data-toggle="modal" data-target="#addBicycleModal" id="addNewBickeModalBtn">Add New Bicycle</button>

                    <!--Bootstrap pop up Modal to add new bicycle -->
                    <div class="modal fade" id="addBicycleModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title">Add Bicycle</h1>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <form action="controllers/addBicycleController.php" method="post">
                                    <div class="modal-body">
                                        <fieldset>
                                            <legend>Client Information</legend>
                                            
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="clientFullName">Full Name <b style="color: red;">*</b></label>
                                                        <input id="clientFullName" class="form-control" type="text" name="clientFullName">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="contactNo">Contact Number <b style="color: red;">*</b></label>
                                                        <input id="contactNo" class="form-control" type="text" name="contactNo">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="email">Email Address <b style="color: red;">*</b></label>
                                                        <input id="email" class="form-control" type="email" name="email">
                                                    </div>
                                                </div>

                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="eventName">Event</label>
                                                        <input id="eventName" class="form-control" type="text" name="eventName">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="raceNo">Race Number</label>
                                                        <input id="raceNo" class="form-control" type="number" name="raceNo">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="clientWeight">Client Weight</label>
                                                        <input id="clientWeight" class="form-control" type="number" name="clientWeight">
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                        
                                        <fieldset>
                                            <legend>Bicycle Information</legend>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="make">Make</label>
                                                        <input id="make" class="form-control" type="text" name="make">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="model">Model</label>
                                                        <input id="model" class="form-control" type="text" name="model">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="comments">Comments</label>
                                                        <textarea id="comments" class="form-control" name="comments" rows="10"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="modal-footer">
                                        <div class="form-group float-right">
                                            <input type="submit" class="btn btn-success" value="Add Bicycle">
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

        <div id="bicycles_container" class="container">
            <div class="row">
                <div class="col-sm-12">
                    <table style="text-align: center;">
                        <th>Bicycle ID</th>
                        <th>Client</th>
                        <th>Make</th>
                        <th>Model</th>
                        <th>Comments</th>

                        <?php 
                            require 'Bicycle.php';

                            $bicycle = new Bicycle();
                            $bicycle->view_all_bicycles((isset($_GET['searchCrit']) ? $_GET['searchCrit'] : ""));
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
                        <a style="color: #f1ff87;" href="../stockManagement/Home.php" class="nav-link"><h4>Stock</h4></a>
                    </li>

                    <li>
                        <a style="color: #f1ff87;" href="../quoteManagement/quoteManagement.php" class="nav-link"><h4>Quote</h4></a>
                    </li>
        
                    <li class="nav-item">
                        <a style="color: #3b3b3b;" href="../BicycleManagement/BicycleManagementView.php" class="nav-link active"><h4>Bicycles</h4></a>
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