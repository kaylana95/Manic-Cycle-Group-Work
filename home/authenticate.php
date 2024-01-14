<?php
session_start();

// connect to database
$db = mysqli_connect('localhost', 'root', '', 'servicemanagement');

// variable declaration
$username = "";
$email    = "";
$errors   = array();

// return user array from their id
function getUserById($id){
    global $db;
    $query = "SELECT * FROM user WHERE id=" . $id;
    $result = mysqli_query($db, $query);

    $user = mysqli_fetch_assoc($result);
    return $user;
}

// escape string
function e($val){
    global $db;
    return mysqli_real_escape_string($db, trim($val));
}

function display_error() {
    global $errors;

    if (count($errors) > 0){
        echo '<div class="error">';
        foreach ($errors as $error){
            echo $error .'<br>';
        }
        echo '</div>';
    }
}

//tells if a user is logged in or not
function isLoggedIn(){
    if (isset($_SESSION['user'])){
        return true;
    }else{
        return false;
    }
}

//log user out if logout button clicked
if (isset($_GET['logout'])){
    session_destroy();
    unset($_SESSION['user']);
    header("location: index.php");
}

// call the login() function if register_btn is clicked
if (isset($_POST['login_btn'])) {
    login();
}

// LOGIN 
function login(){
    global $db, $username, $errors;

    // grap form values
    $username = e($_POST['username']);
    $password = e($_POST['password']);

    // make sure form is filled properly
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    // attempt login if no errors on form
    if (count($errors) == 0) {
        $password = md5($password);

        $query = "SELECT * FROM user WHERE username='$username' AND password='$password' LIMIT 1";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) { 
            // check if user is admin or manager
            $logged_in_user = mysqli_fetch_assoc($results);
            if ($logged_in_user['userType'] == 'administrator') {

                $_SESSION['user'] = $logged_in_user;
                $_SESSION['success']  = "You are now logged in";
                header('location: ownerHome.php');
            }else{
                $_SESSION['user'] = $logged_in_manager;
                $_SESSION['success']  = "You are now logged in";

                header('location: manangerHome.php');
            }
        }else {
            array_push($errors, "Wrong username/password combination");
        }
    }
}

function isAdmin(){
    if (isset($_SESSION['user']) && $_SESSION['user']['userType'] == 'administrator'){
        return true;
    }else{
        return false;
    }
}