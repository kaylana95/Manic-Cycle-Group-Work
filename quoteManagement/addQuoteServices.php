<?php
    $con = mysqli_connect("localhost", "root", "");
    mysqli_select_db($con, "servicemanagement");
    //echo $_POST['quoteID'];
    $insert_quoteServices = mysqli_query($con, "INSERT INTO `quoteservices` (`serviceDescription`, `estimatedPrice`, `quoteID`) VALUES ('" . $_POST['comments'] . "', '" . $_POST['eP'] . "', '" . $_POST['quoteID'] . "')");

    if($insert_quoteServices != FALSE){
        //Update total quote value
        $total = 0;
        
        $quote_result = mysqli_query($con, "SELECT `totalEstimatedPrice` FROM `quote` WHERE `quoteID`='". $_POST['quoteID'] ."'");
        $quote_result = mysqli_fetch_assoc($quote_result);

        $total = $quote_result['totalEstimatedPrice'] + $_POST['eP'];
        echo $total;
        $update_result = mysqli_query($con, "UPDATE `quote` SET `totalEstimatedPrice`='". $total ."' WHERE `quoteID`='". $_POST['quoteID'] ."'");

        if($update_result != FALSE){
            echo "
                <script>
                    alert(\"Quote Services Added\");
                    window.location.href=\"quoteServicesView.php?quoteID=" . $_POST['quoteID'] ."\";
                </script>
            ";
        }
    }else{
        ?>
            <script>
                alert("ERROR: \n <?php echo mysqli_error($con); ?>");
                //window.location.href="../quoteManagement.php";
            </script>
        <?php
    }
?>