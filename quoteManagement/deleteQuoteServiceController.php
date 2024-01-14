<?php
    require '../connect_db.php';

    $result = mysqli_query($con, "DELETE FROM `quoteservices` WHERE `quoteServiceID`='" . $_GET['quoteServiceID'] ."'");

    if($result == FALSE){
        echo mysqli_error($con);
    }else{
        $quote_result = mysqli_query($con, "SELECT `totalEstimatedPrice` FROM `quote` WHERE `quoteID`='". $_GET['quoteID'] ."'");
        $quote_result = mysqli_fetch_assoc($quote_result);
        
        // echo "BEFORE DEDUCTION: " . $quote_result['totalEstimatedPrice'];
        // echo "<br>DEDUCTION AMOUNT: " . $_GET['estimatedPrice'];
        
        $total = $quote_result['totalEstimatedPrice'] - $_GET['estimatedPrice'];
        
        // echo "<br> TOTAL AFTER DEDUCTION: " . $total;

        $update_result = mysqli_query($con, "UPDATE `quote` SET `totalEstimatedPrice`='". $total ."' WHERE `quoteID`='". $_GET['quoteID'] ."'");
        header('Location: quoteServicesView.php?quoteID='. $_GET['quoteID']);
    }
?>