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

<div class="add">
  <form action="" method="post" enctype="multipart/form-data" class="addoredit">

    <div class = "addname">
      <h1 class = "addstuff">Add New Stock Item</h1>
      
      <?php addProduct(); 
      displayMsg();?>
    </div>
    <div class = "back">
    <div>
      <form>
      <table>



        <tr>
          <td> <input type="text" name="stockName" placeholder="Enter Stock Name" class="form-control"> </td>
          <td> <input type="number" name="costPrice" placeholder="Enter Stock Cost Price" class="form-control" size="60"> </td>

          <td> <input type="number" name="sellPrice" placeholder="Enter Stock Sell Price" class="form-control"></td>
        </tr>


        <!-- Product Categories-->

        <tr>
        <td> <label for="stockBrand">Stock Brand</label><br>

          <select class="" name=stockBrand>
            <option value="">Select Brand</option>
            <?php //calls the function to show brands
            showBrand(); 
            ?>

          </select>
          </td>
          <td> <label for="stockCate">Stock Category</label><br>

          <select class="" name=stockCate>
            <option value="">Select Category</option>
            <?php //calls the function to show categories 
            showCat(); 
            ?>

          </select>
          </td>
          <td> <input type="number" class="form-control" placeholder="Enter Stock Count" name=stockCount> </td></tr>

          <tr><td>  <textarea name="stockDesc" placeholder="Enter Stock Description" id="" cols="30" rows="10" class="add"></textarea></td>
        </tr>
      </table>
      
      <div class="form-group">

        <input type="submit" name="publish" class="addbtn" value="Add New Item">
      </div>

    </div>

  </form>
</div>
</div>
<?php require_once("footer.php"); ?>