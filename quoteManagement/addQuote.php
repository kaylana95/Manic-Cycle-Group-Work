<?php
    // require 'quote.php';

    $con = mysqli_connect("localhost", "root", "");
    mysqli_select_db($con, "servicemanagement");

    function getClientID($name, $con){
        echo $name;

        $result = mysqli_query($con, "SELECT `clientID` FROM `client` WHERE `clientFullname`='" . $name ."'");
        $result = mysqli_fetch_assoc($result);

        echo "<script>alert(\"CLIENT ID: \" ". $result['clientID'] . " );</script>";
        return $result['clientID'];
    }

    function insertQuoteData($con){
        $clientID = getClientID($_POST['clientFullName'], $con);
        $sql = "INSERT INTO `quote` (`producedOn`, `producedAt`, `clientID`, `totalEstimatedPrice`) VALUES ('". $_POST['purDate'] ."', '" . $_POST['serialNumber'] . "', '". $clientID ."', '0')";
        $status = "inserted";

        // if(($quote_detials = checks($_POST['clientID'], $con)) != FALSE){
        //     $sql = "UPDATE `quote` SET `producedOn`='". $_POST['purDate'] ."', `serialNumber`='" . $_POST['producedAt'] . "', `clientID`='". $_POST['clientFullName'] ."', `totalEstimatedPrice`='" . $_POST['eP'] . "' WHERE `clientID`='". $_POST['clientID'] ."'";
        //     $status = "updated";
        // }

        $result = mysqli_query($con, $sql);

        if($result != FALSE){
            echo "
                <script>
                    alert(\"quote details " . $status .".\");
                    window.location.href=\"quoteManagement.php\";
                </script>
            ";
        }else{
            echo "
                <script>
                    alert(\"AN ERROR HAS OCCURRED: ". mysqli_error($con) ." \");
                    //window.location.href=\"../quoteManagement.php\";
                </script>
            ";
        }
    }

    insertQuoteData($con);

function getQuoteID($id, $con){
        $select_result = mysqli_query($con, "SELECT `quoteID` FROM `client` WHERE `clientFullname`='" . $fullName . "'");
        $select_result = mysqli_query($con, "SELECT `quoteID` FROM `clientID` WHERE `?`='" . $id . "'");

        if($select_result == FALSE){
            echo "
                <script>alert(\"An error occured while inserting quote details: \" ". mysqli_error($con) .")</script>
            ";
        }

        $row = mysqli_fetch_assoc($select_result);
        echo "
            <script>alert(\"Quote NO:\" ". $row['quoteID'] .")</script>
        ";

        return $row['quoteID'];
    }