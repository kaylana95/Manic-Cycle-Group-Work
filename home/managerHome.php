<?php 
    session_start();

    //validate user login
    if(isset($_SESSION['type'])){
        if($_SESSION['type'] != "manager"){
            if($_SESSION['type'] != "administrator"){
                header('Location: ../serviceManagement/servicemanagerMechanic.php');
                return;
            }else{
                header('Location: ../home/ownerHome.php');
                return;
            }
        }
    }else{
        header('Location: ../index.php');
        return;
    }
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>Cover Template · Bootstrap</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/cover/">

    <!-- Bootstrap core CSS -->
    <link href="../assets/dist/css/bootstrap.css" rel="stylesheet">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        body {
            background-image: url("../bg-image-body.jpg");
            background-size: cover;
            }

        .text-center {
            padding-bottom: 5%;
        }

        .button {
            border-radius: 4px;
            background-color: #000000;
            border: none;
            color: #F9FE00;
            text-align: center;
            font-size: 14px;
            padding: 20px;
            width: 200px;
            transition: all 0.5s;
            cursor: pointer;
            margin: 5px;
        }

        .button span {
            cursor: pointer;
            display: inline-block;
            position: relative;
            transition: 0.5s;
        }

        .button span:after {
            content: '\00bb';
            position: absolute;
            opacity: 0;
            top: 0;
            right: -20px;
            transition: 0.5s;
        }

        .button:hover span {
            padding-right: 25px;
        }

        .button:hover span:after {
            opacity: 1;
            right: 0;
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="homeScreen.css" rel="stylesheet">
</head>

<img src="logo.png" alt="Logo" width="175" height="100" />

<body class="text-center">
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <header class="masthead mb-auto">

            <nav class="nav nav-masthead justify-content-right">
                <a class="nav-link active" href="#"> 
                    <?php 
                        //Code contributed by Hugh-Martin
                        if(isset($_SESSION['first']) && isset($_SESSION['second'])){
                            echo $_SESSION['first'] . " " . $_SESSION['second'];
                        }
                    ?>    
                </a>
            </nav>

        </header>

        <main role="main" class="inner cover">
            <h1 class="cover-heading">Managers Home Page.</h1>
            <p class="lead">please select which page you would like to view</p>

            <div class="container">


            <div class="text-center">
                    <button class="button" onclick="window.location.href='../quoteManagement/quoteManagement.php'" style="vertical-align:middle"><span>Quote Manager</span></button>
                </div>

                <div class="text-center">
                    <button class="button" onclick="window.location.href='../bicycleManagement/bicycleManagementView.php'" style="vertical-align:middle"><span>Bicycle Information Manager</span></button>
                </div>

                <div class="text-center">
                    <button class="button" onclick="window.location.href='../serviceManagement/servicemanager.php'" style="vertical-align:middle"><span>Service Manager</span></button>
                </div>

                <div class="text-center">
                    <button class="button" onclick="window.location.href='../stockManagement/Home.php'" style="vertical-align:middle"><span>Stock Manager</span></button>
                </div>

            </div>

        </main>

        <footer class="mastfoot mt-auto">

        </footer>
    </div>
</body>

</html>
