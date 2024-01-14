<?php 
  session_start();
  //validate user login
  if(isset($_SESSION['type'])){
    if($_SESSION['type'] != "administrator" && $_SESSION['type'] != "manager"){
        header('Location: ../serviceManagement/servicemanagerMechanic.php');
        return;
    }
}else{
    header('Location: ../index.php');
    return;
}
?>
<?php require_once("backend/config.php")?>
<?php require_once("header.php"); ?>
<section class="container">

    <div class="left-half">
        <article>
<?php addBrand(); ?> 
            <h1 class = "addstuff addname">Stock Brands</h1>
            
            <h3><?php displayMsg(); ?></h3>
            <table>
            <th>Brand ID</th>
                <th>Brand Name</th>
                <th></th>
            </tr>
                <?php getBrand(); ?>
            </table>

        </article>
    </div>
    <div class="right-half">
        <article>
            <h1 class = "addstuff addname">Add New Brand</h1>
            <form class = "formcss" method="post">
<table>
    <tr><td><input type="text" name="brandName" placeholder="Enter Brand Name"></td> 
    <td> <button class = "change"  type="submit" name = "addBrand">Add</button></td>
</tr>
</table>
            </form>
        </article>
    </div>

</section>
<div class = "morespace"></div>
<?php require_once("footer.php"); ?>