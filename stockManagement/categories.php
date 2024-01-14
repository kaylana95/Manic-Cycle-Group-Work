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
            <h1 class = "addstuff addname">Stock Categories</h1>
            <h3><?php displayMsg(); ?></h3>
            <?php addCat(); ?>
            <table>
                <th>Category ID</th>
                <th>Category Name</th>
                <th> </th>
                
                <?php getCat(); ?>
            </table>
        </article>
    </div>
    <div class="right-half">
        <article>
            <h1 class = "addstuff addname">Add New Category</h1>
            <form class = "formcss" method="post">
<table>
    <tr><td><input type="text" name="catName" placeholder="Enter Category Name"></td> 
    <td> <button class = "change" type="submit" name= "addCat">Add</button></td>
</tr>
</table>
            </form>
        </article>
    </div>
    <?php require_once("footer.php"); ?>
</section>
