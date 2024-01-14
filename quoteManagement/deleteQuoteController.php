<?php
    require '../connect_db.php';

    $result = mysqli_query($con, "DELETE FROM `quote` WHERE `quoteID`='" . $_GET['quoteID'] ."'");

    if($result != FALSE){
        header('Location: quoteManagement.php');
        return;
    }
?>