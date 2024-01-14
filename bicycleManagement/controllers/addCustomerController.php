<?php
    $con = mysqli_connect("localhost", "root", "");
    mysqli_select_db($con, "servicemanagement");

    $insert_cust_result = mysqli_query($con, "INSERT INTO `client` (`clientFullname`, `phoneNumber`, `emailAddress`, `clientWeight`, `event`, `raceNumber`) VALUES ('" . $_POST['clientFullName'] . "', '" . $_POST['contactNo'] . "', '" . $_POST['emailAddress'] . "', '" . $_POST['clientWeight'] . "', '" . $_POST['custEvent'] . "', '" . $_POST['raceNo'] . "')");

    if($insert_cust_result == TRUE){
        ?>
            <script>
                alert("New Customer Added");
                window.location.href="../bicycleManagementView.php";
            </script>
        <?php
    }else{
        ?>
            <script>
                alert("An ERROR has occurred boet: \n <?php echo mysqli_error($con); ?>");
                window.location.href="../bicycleManagementView.php";
            </script>
        <?php
    }
?>