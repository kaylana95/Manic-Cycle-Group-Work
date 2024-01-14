<?php 

    require '../../connect_db.php';

    //Check db
    $select = mysqli_query($con, "SELECT * FROM `frame` WHERE `bicycleID`='". $_POST['bikeId'] ."'");

    if(mysqli_num_rows($select) > 0){
        $results = mysqli_query($con, "UPDATE `frame` SET `hardtailOrDualSus`='". $_POST['hardTailOrDualSus'] ."', `frameMake`='". $_POST['frameMake'] ."', `frameModel`='". $_POST['frameModel'] ."', `frameComments`='". $_POST['frameComments'] ."' WHERE `bicycleID`='". $_POST['bikeId'] ."'");

        if($results == FALSE){
            echo "<script>alert(\"1 - An ERR occurred: ". mysqli_error($con) ."\");</script>";
        }else{
            echo "<script>alert(\"Frame information updated\");</script>";
            header('Location: ../bicycleManagementView.php');
        }
    }else{
        $insert = mysqli_query($con, "INSERT INTO `frame`(`bicycleID`, `hardtailOrDualSus`, `frameMake`, `frameModel`, `frameComments`) VALUES('". $_POST['bikeId'] ."', '". $_POST['hardTailOrDualSus'] ."', '". $_POST['frameMake'] ."', '". $_POST['frameModel'] ."', '". $_POST['frameComments'] ."')");

        if($insert == FALSE){
            echo "<script>alert(\"2 - An ERR occurred: ". mysqli_error($con) ."\");</script>";
        }else{
            echo "<script>alert(\"Frame information updated\");</script>";
            header('Location: ../bicycleManagementView.php');
        }
    }

    
?>