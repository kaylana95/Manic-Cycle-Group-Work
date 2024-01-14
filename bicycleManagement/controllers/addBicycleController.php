<?php
    require '../Bicycle.php';

    $con = mysqli_connect("localhost", "root", "");
    mysqli_select_db($con, "servicemanagement");
    
    function getClientID($fullName, $con){
        $select_result = mysqli_query($con, "SELECT `clientID` FROM `client` WHERE `clientFullname`='" . $fullName . "'");

        if($select_result == FALSE){
            echo "
                <script>alert(\"AN ERROR OCCURRED WHILE INSERTING BICYCLE INFO: \" ". mysqli_error($con) .")</script>
            ";
        }

        $row = mysqli_fetch_assoc($select_result);
        echo "
            <script>alert(\"Client NO:\" ". $row['clientID'] .")</script>
        ";

        return $row['clientID'];
    }

    function checkClientExistince($clientFullName, $con){
        $check_result = mysqli_query($con, "SELECT * FROM `client` WHERE `clientFullname`='". $clientFullName ."'");

        if(mysqli_num_rows($check_result) > 0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function insertClientData($con){
        $sql = "INSERT INTO `client` (`clientFullname`, `phoneNumber`, `emailAddress`, `clientWeight`, `event`, `raceNumber`) VALUES ('". $_POST['clientFullName'] ."', '" . $_POST['contactNo'] . "', '". $_POST['email'] ."', '". $_POST['clientWeight'] ."', '". $_POST['eventName'] ."', '" . $_POST['raceNo'] . "')";
        $status = "inserted";

        if(($client_info = checkClientExistince($_POST['clientFullName'], $con)) != FALSE){
            $sql = "UPDATE `client` SET `clientFullname`='". $_POST['clientFullName'] ."', `phoneNumber`='" . $_POST['contactNo'] . "', `emailAddress`='". $_POST['email'] ."', `clientWeight`='". $_POST['clientWeight'] ."', `event`='". $_POST['eventName'] ."', `raceNumber`='" . $_POST['raceNo'] . "' WHERE `clientFullname`='". $_POST['clientFullName'] ."'";
            $status = "updated";
        }

        $result = mysqli_query($con, $sql);

        if($result != FALSE){
            echo "
                <script>
                    alert(\"client information " . $status .".\");
                    //window.location.href=\"../bicycleManagementView.php\";
                </script>
            ";
        }else{
            echo "
                <script>
                    alert(\"AN ERROR HAS OCCURRED WHILE INSERTING CLIENT DATA: ". mysqli_query($con) ." \");
                    //window.location.href=\"../bicycleManagementView.php\";
                </script>
            ";
        }
    }

    insertClientData($con);

    //$clientNo, $component, $make, $model, $comments
    $bicycle = new Bicycle(getClientID($_POST['clientFullName'], $con), $_POST['make'], $_POST['model'], $_POST['comments']);
    $bicycle->create_bicycle();
?>