<?php

    class Bicycle{
        private $clientNo, $component, $make, $model, $comments;

        public function __construct(){
            $arg_vals = func_get_args();

            switch(func_num_args()){
                case 4:
                    $this->__construct2($arg_vals[0], $arg_vals[1], $arg_vals[2], $arg_vals[3]);
                default:
                    $this->__construct1();
            }
        }

        public function __construct1(){
            //default
        }

        public function __construct2($clientNo, $make, $model, $comments){
            $this->clientNo = $clientNo;
            $this->make = $make;
            $this->model = $model;
            $this->comments = $comments;
        }

        public function getClientName($userID){
            $con = $this->connect_db();

            $result = mysqli_query($con, "SELECT `clientFullname` FROM `client` WHERE `clientID`='". $userID ."'");
            $row = mysqli_fetch_assoc($result);

            return $row['clientFullname'];
        }

        public function create_bicycle(){
            $con = $this->connect_db();

            $sql_insert_bike = "INSERT INTO `bicycle` (`clientID`, `component`, `make`, `model`, `comments`) VALUES ('" . $this->clientNo . "', '" . $this->component . "', '" . $this->make . "', '" . $this->model . "', '" . $this->comments . "')";
            $insert_result = mysqli_query($con, $sql_insert_bike);

            if($insert_result == TRUE){
                //If successful open specific bike information sheet
                echo "
                    <script>
                        alert(\"New Bicycle Created\");
                        window.location.href=\"../bicycleManagementView.php\"
                    </script>
                ";
            }else{
                echo "
                    <script>
                        alert(\"ERROR: ". mysqli_error($con) ."\");
                        //window.location.href=\"../bicycleManagementView.php\"
                    </script>
                ";
            }
        }

        public function view_all_bicycles($searchCrit = ""){
            $con = $this->connect_db();

            $sql_select_all = "SELECT * FROM `bicycle` ORDER BY `clientID` DESC";
            $select_result = mysqli_query($con, $sql_select_all);

            if($searchCrit == ""){
                if(mysqli_num_rows($select_result) > 0){
                    while($row = mysqli_fetch_assoc($select_result)){
                        ?>
                            <tr id="content_row" onclick="openBicycle(<?php echo $row['bicycleID']; ?>)">
                                <td><?php echo $row['bicycleID']; ?></td>
                                <td>
                                    <?php 
                                        $obj = new Bicycle(); 
                                        echo  $obj->getClientName($row['clientID']);
                                    
                                    ?>
                                </td>
                                <td><?php echo $row['make']; ?></td>
                                <td><?php echo $row['model']; ?></td>
                                <td><?php echo $row['comments']; ?></td>
                                
                            </tr>
                        <?php
                    }
                }else{
                    echo "<h2>No Bicycles Found </h2>";
                }
            }else if($this->checkClientNameExists($searchCrit) != FALSE){
                if(mysqli_num_rows($select_result) > 0){
                    $all_bicycles = $select_result->fetch_all();

                    foreach($all_bicycles as $bicycle){
                        if(array_search($this->checkClientNameExists($searchCrit), $bicycle)){
                            ?>
                                <tr id="content_row" onclick="openBicycle(<?php echo $row['bicycleID']; ?>)">
                                    <td><?php echo $bicycle[0]; ?></td>
                                    <td>
                                        <?php 
                                            $obj = new Bicycle(); 
                                            echo  $obj->getClientName($bicycle[1]);
                                        
                                        ?>
                                    </td>
                                    <td><?php echo $bicycle[3]; ?></td>
                                    <td><?php echo $bicycle[4]; ?></td>
                                    <td><?php echo $bicycle[5]; ?></td>
                                </tr>
                            <?php
                        }
                    }
                }
            }else{
                //perform a search
                if(mysqli_num_rows($select_result) > 0){
                    $all_bicycles = $select_result->fetch_all();
                    
                    foreach($all_bicycles as $bicycle){
                        if($this->arr_srch($searchCrit, $bicycle) != FALSE || $searchCrit === $bicycle[0]){
                            ?>
                                <tr id="content_row" onclick="openBicycle(<?php echo $bicycle[0]; ?>)">
                                    <td><?php echo $bicycle[0]; ?></td>
                                    <td>
                                        <?php 
                                            $obj = new Bicycle(); 
                                            echo  $obj->getClientName($bicycle[1]);
                                        
                                        ?>
                                    </td>
                                    <td><?php echo $bicycle[3]; ?></td>
                                    <td><?php echo $bicycle[4]; ?></td>
                                    <td><?php echo $bicycle[5]; ?></td>
                                </tr>
                            <?php
                        }else{
                            echo "<script>alert(\"No results Found\"); window.location.href=\"bicycleManagementView.php\";</script>";
                           
                        }
                    }
                }
            }
        }

        public function checkClientNameExists($name){
            $con = $this->connect_db();

            $select_result = mysqli_query($con, "SELECT `clientID`, `clientFullname` FROM `client`");
            $all_client_names = $select_result->fetch_all();

            foreach($all_client_names as $client_name){
                if($client_name[1] == $name){
                    return $client_name[0];
                }
            }

            $con->close();
            return false;
        }

        public function delete_bicycle($bicycleId){
            $con = $this->connect_db();

            $delete_result = mysqli_query($con, "DELETE FROM `bicycle` WHERE `bicycleID`='". $bicycleId ."'");

            if($delete_result == FALSE){
                echo "<script>alert(\"An ERR occurred: ". mysqli_error($con) ."\");</script>";
            }else{
                echo "<script>alert(\"Bicycle deleted\");</script>";
                header('Location: ../bicycleManagementView.php');
            }
        }

        public function updateBicycleInfo($bicycleId){
            $con = $this->connect_db();

            $select_result = mysqli_query($con, "SELECT * FROM `bicycle` WHERE `bicycleID`='" . $bicycleId . "'");
            $curr_vals = mysqli_fetch_assoc($select_result);

            if($curr_vals['component'] != $_POST['component']){
                
            }
            
            $con->close();
        }

        public function arr_srch($needle, $haystack){
            foreach($haystack as $arr_item){
                ($needle == $arr_item ? true : false);
            }
        }

        public function connect_db(){
            $con = mysqli_connect("localhost", "root", "");
            mysqli_select_db($con, "servicemanagement");

            return $con;
        }

        //Getters & Setters
        public function getClientNo()
        {
                return $this->clientNo;
        }

        public function setClientNo($clientNo)
        {
                $this->clientNo = $clientNo;

                return $this;
        }

        public function getComponent()
        {
                return $this->component;
        }

        public function setComponent($component)
        {
                $this->component = $component;

                return $this;
        }

        public function getMake()
        {
                return $this->make;
        }

        public function setMake($make)
        {
                $this->make = $make;

                return $this;
        }

        public function getModel()
        {
                return $this->model;
        }

        public function setModel($model)
        {
                $this->model = $model;

                return $this;
        }

        public function getComments()
        {
                return $this->comments;
        }

        public function setComments($comments)
        {
                $this->comments = $comments;

                return $this;
        }
    }
?>