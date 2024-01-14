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
    
    $con = mysqli_connect("localhost", "root", "");
    mysqli_select_db($con, "servicemanagement");

    $bicycleInfo = mysqli_query($con, "SELECT * FROM `bicycle` WHERE `bicycleID`='". $_GET['bikeId'] ."'");
    $bicycleRowInfo = mysqli_fetch_assoc($bicycleInfo);

    $clientInfo = mysqli_query($con, "SELECT * FROM `client` WHERE `clientID`='". $bicycleRowInfo['clientID'] ."'");
    $frameInfo = mysqli_query($con, "SELECT * FROM `frame` WHERE `bicycleID`='". $_GET['bikeId'] ."'");
    $suspensionInfo = mysqli_query($con, "SELECT * FROM `suspension` WHERE `bicycleID`='". $_GET['bikeId'] ."'");
    $wheelSetInfo = mysqli_query($con, "SELECT * FROM `wheelset` WHERE `bicycleID`='". $_GET['bikeId'] ."'");
    $driveTrainInfo = mysqli_query($con, "SELECT * FROM `drivetrain` WHERE `bicycleID`='". $_GET['bikeId'] ."'");
    $brakesInfo = mysqli_query($con, "SELECT * FROM `brakes` WHERE `bicycleID`='". $_GET['bikeId'] ."'");
    $miscInfo = mysqli_query($con, "SELECT * FROM `miscelanious` WHERE `bicycleID`='". $_GET['bikeId'] ."'");

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="../UserManagement/styles.css">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="icon" href="../Logo.jpg">
        <title>Bicycle Management</title>

        <script src="scripts/updateBicycleInfoScript.js"></script>
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
        <hr style="height: 2px; background-color: gray;">
    </header>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title" style="text-align: center;">#<?php echo $_GET['bikeId']; ?></h2>
                            <h3 style="text-align: center;">
                                <?php 
                                
                                    if(mysqli_num_rows($clientInfo) > 0){
                                        $clientRow = mysqli_fetch_assoc($clientInfo);
                                        echo $clientRow['clientFullname'];
                                    } 
                                
                                ?>
                            </h3>

                            <button style="margin-left: 45%;" type="button" class="allButtons" onclick="window.location.href='controllers/deleteBicycleController.php?bikeId=<?php echo $_GET['bikeId']; ?>'">Delete Bicycle</button>
                        </div>

                        <div class="card-body">
                            <form action="controllers/updateBikeInfoController.php" method="POST">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <fieldset>
                                            <legend>Bicycle Information</legend>
                                                    
                                            <div class="form-group">
                                                <label for="component">Component</label>
                                                <input id="component" class="form-control" type="text" name="component" value="<?php echo $bicycleRowInfo['component']; ?>" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label for="make">Make</label>
                                                <input id="make" class="form-control" type="text" name="make" value="<?php echo $bicycleRowInfo['make']; ?>" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label for="model">Model</label>
                                                <input id="model" class="form-control" type="text" name="model" value="<?php echo $bicycleRowInfo['model']; ?>" disabled>
                                            </div>

                                            <input type="text" value="<?php echo $bicycleRowInfo['bicycleID']; ?>" id="bikeId" name="bikeId" hidden>

                                            <div class="form-group">
                                                <label for="comments">Comments</label>
                                                <textarea id="comments" class="form-control" name="comments" rows="3" disabled><?php echo $bicycleRowInfo['comments']; ?></textarea>
                                            </div>
                                        </fieldset>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                        
                                        <button id="editBikeBtn" style="margin-top: 75%;" type="button" onclick="editBikeInfo()" class="allButtons">Edit Bicycle Info</button>
                                        <button id="saveBikeBtn" style="margin-top: 75%;"type="submit" class="allButtons" hidden>Save</button>
                                    </div>
                                </div>
                            </form>
                            
                            <form action="controllers/updateFrameInfoController.php" method="post">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <fieldset>
                                            <legend>Frame Information</legend>
                                            <?php 
                                                if(mysqli_num_rows($frameInfo) > 0){
                                                    $frameRow = mysqli_fetch_assoc($frameInfo);
                                                }
                                            ?>
                                            <div class="form-group">
                                                <label for="hardTailOrDualSus">Hard Tail/ Duel Sus</label>
                                                <input id="hardTailOrDualSus" class="form-control" type="text" name="hardTailOrDualSus" value="<?php echo (isset($frameRow['hardtailOrDualSus']) ? $frameRow['hardtailOrDualSus'] : ""); ?>" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label for="frameMake">Frame Make</label>
                                                <input id="frameMake" class="form-control" type="text" name="frameMake" value="<?php echo (isset($frameRow['frameMake']) ? $frameRow['frameMake'] : ""); ?>" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label for="frameModel">Frame Model</label>
                                                <input id="frameModel" class="form-control" type="text" value="<?php echo (isset($frameRow['frameModel']) ? $frameRow['frameModel'] : ""); ?>" name="frameModel" disabled>
                                            </div>

                                            <input type="text" value="<?php echo (isset($bicycleRowInfo['bicycleID']) ? $bicycleRowInfo['bicycleID'] : ""); ?>" id="bikeId" name="bikeId" hidden>
                                            
                                            <div class="form-group">
                                                <label for="frameComments">Comments</label>
                                                <textarea id="frameComments" class="form-control" name="frameComments" rows="3" disabled><?php echo (isset($frameRow['frameComments']) ? $frameRow['frameComments'] : ""); ?></textarea>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="col-sm-6">
                                        
                                        <button style="margin-top: 75%;" id="editFrameInfoBtn" type="button" onclick="editFrameInfo()" class="allButtons">Edit Frame Info</button>
                                        <button id="saveFrameInfoBtn" style="margin-top: 75%;"type="submit" class="allButtons" hidden>Save</button>
                                    </div>
                                </div>
                            </form>

                            <form>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <fieldset>
                                            <legend>Suspension Information</legend>

                                            <?php 
                                                if(mysqli_num_rows($suspensionInfo) > 0){
                                                    $suspensionRow = mysqli_fetch_assoc($suspensionInfo);
                                                }
                                            ?>

                                            <div class="form-group">
                                                <label for="frontForkMake">Front Fork Make</label>
                                                <input id="frontForkMake" class="form-control" type="text" name="frontForkMake" value="<?php echo (isset($suspensionRow['frontForkMake']) ? $suspensionRow['frontForkMake'] : ""); ?>" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label for="frontForkModel">Front Fork Model</label>
                                                <input id="frontForkModel" class="form-control" type="text" name="frontForkModel" value="<?php echo (isset($suspensionRow['frontForkModel']) ? $suspensionRow['frontForkModel'] : ""); ?>" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label for="frontForkComments">Front Fork Comments</label>
                                                <textarea id="frontForkComments" class="form-control" name="frontForkComments" rows="3" disabled><?php echo (isset($suspensionRow['frontForkComments']) ? $suspensionRow['frontForkComments'] : ""); ?></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="rearShockMake">Rear Shock Make</label>
                                                <input id="rearShockMake" class="form-control" type="text" name="rearShockMake" value="<?php echo (isset($suspensionRow['rearShockMake']) ? $suspensionRow['rearShockMake'] : ""); ?>" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label for="rearShockModel">Rear Shock Model</label>
                                                <input id="rearShockModel" class="form-control" type="text" name="rearShockModel" value="<?php echo (isset($suspensionRow['rearShockModel']) ? $suspensionRow['rearShockModel'] : ""); ?>" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label for="rearShockComments">Rear Shock Comments</label>
                                                <textarea id="rearShockComments" class="form-control" name="rearShockComments" rows="3" disabled><?php echo (isset($suspensionRow['rearShockComments']) ? $suspensionRow['rearShockComments'] : ""); ?></textarea>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="col-sm-6">
                                        
                                        <button style="margin-top: 117%;" type="button" onclick="editSuspensionInfo()" class="allButtons">Edit Suspension Info</button>
                                        
                                    </div>
                                </div>
                            </form>

                            <form>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <fieldset>
                                            <legend>Wheel Set Information</legend>
                                            <?php 
                                                if(mysqli_num_rows($wheelSetInfo) > 0){
                                                    $wheelSetRow = mysqli_fetch_assoc($wheelSetInfo);
                                                }
                                            ?>
                                            <!--Front Hub-->
                                            <div class="form-group">
                                                <label for="frontHubMake">Front Hub Make</label>
                                                <input id="frontHubMake" class="form-control" type="text" name="frontHubMake" value="<?php echo (isset($wheelSetRow['frontHubMake']) ? $wheelSetRow['frontHubMake'] : ""); ?>" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label for="frontHubModel">Front Hub Model</label>
                                                <input id="frontHubModel" class="form-control" type="text" name="frontHubModel" value="<?php echo (isset($wheelSetRow['frontHubModel']) ? $wheelSetRow['frontHubModel'] : ""); ?>" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label for="frontHubComments">Front Hub Comments</label>
                                                <textarea id="frontHubComments" class="form-control" name="frontHubComments" rows="3" disabled><?php echo(isset($wheelSetRow['frontHubComments']) ? $wheelSetRow['frontHubComments'] : ""); ?></textarea>
                                            </div>

                                            <!--Rear Hub-->
                                            <div class="form-group">
                                                <label for="rearHubMake">Rear Hub Make</label>
                                                <input id="rearHubMake" class="form-control" type="text" name="rearHubMake" value="<?php echo (isset($wheelSetRow['rearHubMake']) ? $wheelSetRow['rearHubMake'] : ""); ?>" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label for="rearHubModel">Rear Hub Model</label>
                                                <input id="rearHubModel" class="form-control" type="text" name="rearHubModel" value="<?php echo (isset($wheelSetRow['rearHubModel']) ? $wheelSetRow['rearHubModel'] : ""); ?>" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label for="rearHubComments">Rear Hub Comments</label>
                                                <textarea id="rearHubComments" class="form-control" name="rearHubComments" rows="3" disabled><?php echo(isset($wheelSetRow['rearHubComments']) ? $wheelSetRow['rearHubComments'] : ""); ?></textarea>
                                            </div>

                                            <!--rims-->
                                            <div class="form-group">
                                                <label for="rimsMake">Rims Make</label>
                                                <input id="rimsMake" class="form-control" type="text" name="rimsMake" value="<?php echo (isset($wheelSetRow['rimsMake']) ? $wheelSetRow['rimsMake'] : ""); ?>" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label for="rimsModel">Rims Model</label>
                                                <input id="rimsModel" class="form-control" type="text" name="rimsModel" value="<?php echo (isset($wheelSetRow['rimsModel']) ? $wheelSetRow['rimsModel'] : ""); ?>" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label for="rimsComments">Rims Comments</label>
                                                <textarea id="rimsComments" class="form-control" name="rimsComments" rows="3" disabled><?php echo(isset($wheelSetRow['rimsComments']) ? $wheelSetRow['rimsComments'] : ""); ?></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="numSpokes">Number of Spokes</label>
                                                <input id="numSpokes" class="form-control" type="number" name="numSpokes" value="<?php echo (isset($wheelSetRow['numSpokes']) ? $wheelSetRow['numSpokes'] : ""); ?>" disabled>
                                            </div>

                                            <!--front tyre-->
                                            <div class="form-group">
                                                <label for="frontTyreMake">Front Tyre Make</label>
                                                <input id="frontTyreMake" class="form-control" type="text" name="frontTyreMake" value="<?php echo (isset($wheelSetRow['frontTyreMake']) ? $wheelSetRow['frontTyreMake'] : ""); ?>" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label for="frontTyreTubeless">Front Tyre Tubeless</label>
                                                <input id="frontTyreTubeless" class="form-control" type="text" name="frontTyreTubeless" value="<?php echo (isset($wheelSetRow['frontTyreTubeless']) ? $wheelSetRow['frontTyreTubeless'] : ""); ?>" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label for="frontTyrePressure">Front Tyre Pressure</label>
                                                <input id="frontTyrePressure" class="form-control" type="text" name="frontTyrePressure" value="<?php echo (isset($wheelSetRow['frontTyrePressure']) ? $wheelSetRow['frontTyrePressure'] : ""); ?>" disabled>
                                            </div>

                                            <!--front tyre-->
                                            <div class="form-group">
                                                <label for="rearTyreMake">Rear Tyre Make</label>
                                                <input id="rearTyreMake" class="form-control" type="text" name="rearTyreMake" value="<?php echo (isset($wheelSetRow['rearTyreMake']) ? $wheelSetRow['rearTyreMake'] : ""); ?>" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label for="rearTyreTubeless">Rear Tyre Tubeless</label>
                                                <input id="rearTyreTubeless" class="form-control" type="text" name="rearTyreTubeless" value="<?php echo (isset($wheelSetRow['rearTyreTubeless']) ? $wheelSetRow['rearTyreTubeless'] : ""); ?>" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label for="rearTyrePressure">Rear Tyre Pressure</label>
                                                <input id="rearTyrePressure" class="form-control" type="text" name="rearTyrePressure" value="<?php echo (isset($wheelSetRow['rearTyrePressure']) ? $wheelSetRow['rearTyrePressure'] : ""); ?>" disabled>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="col-sm-6">
                                        
                                        <button style="margin-top: 292%;" type="button" onclick="editWheelsetInfo()" class="allButtons">Edit Wheelset Info</button>
                                        
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    <footer style="padding-top: 5%;">
        <nav class="navbar navbar-expand-sm fixed-bottom">
                <hr style="height: 2px; background-color: gray;">

                <ul class="navbar-nav nav-tabs">
                    <li class="nav-item">
                        <a href="../UserManagement/userManagementView.php" style="color: #f1ff87;" class="nav-link"><h4>Users</h4></a>
                    </li>

                    <li class="nav-item">
                        <a style="color: #f1ff87;" href="" class="nav-link"><h4>Services</h4></a>
                    </li>
            
                    <li class="nav-item">
                        <a style="color: #f1ff87;" href="" class="nav-link"><h4>Stock</h4></a>
                    </li>
        
                    <li class="nav-item">
                        <a style="color: #3b3b3b;" href="../BicycleManagement/BicycleManagementView.php" class="nav-link active"><h4>Bicycles</h4></a>
                    </li>

                    <li class="nav-item ml-5">
                        <a style="color: #82ffc3;" onclick="logoutBtn()" class="nav-link"><h4>Logout</h4></a>
                    </li>

                    <li class="nav-item">
                        <a style="color: #82ffc3;" onclick="" class="nav-link"><h4>Help</h4></a>
                    </li>
                </ul>
                <hr style="height: 2px; background-color: gray;">
            </nav>
    </footer>
</html>