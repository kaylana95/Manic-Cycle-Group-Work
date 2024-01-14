<?php
    require '../../connect_db.php';
    
    $result = mysqli_query($con, "UPDATE `bicycle` SET `component`='". $_POST['component'] ."', `make`='". $_POST['make'] ."', `model`='" . $_POST['model'] . "', `comments`='". $_POST['comments'] ."' WHERE `bicycleID`='". $_POST['bikeId'] ."'");

    if($result == FALSE){
        echo "<script>alert(\"An ERR occurred: ". mysqli_error($con) ."\");</script>";
    }else{
        echo "<script>alert(\"Bicycle information updated\");</script>";
        header('Location: ../bicycleManagementView.php');
    }
?>