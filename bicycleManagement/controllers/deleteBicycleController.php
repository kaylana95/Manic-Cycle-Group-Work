<?php 
    require '../Bicycle.php';

    $bicycle = new Bicycle();

    $bicycle->delete_bicycle($_GET['bikeId']);
?>