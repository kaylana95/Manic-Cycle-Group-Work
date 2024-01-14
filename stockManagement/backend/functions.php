<?php
function query($sql)
{
//Queries the specified SQL and returns the results of the query
    global $connection;
    return mysqli_query($connection, $sql);
}

function confirm($result)
{//provides info on failed queries
    global $connection;
    if (!$result) {
        die("QUERY FAILED" . mysqli_error($connection));
    }
}
function setMessage($msg)
{
//Sets messages to be displayed in any place. Uses displayMsg() to display the message.
    if (!empty($msg)) {
        $_SESSION['message'] = $msg;
    } else {

        $msg = "";
    }
}
function displayMsg()
{//Used by setMessage()
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}
function redirect($location)
{
    //redirects user to specified page

    header("Location: $location");
}
function escape_string($string)
{//prevents SQL Injection
    global $connection;
    return mysqli_real_escape_string($connection, $string);
}

function fetch_array($result)
{//fetches rows of data from a Database
    return mysqli_fetch_array($result);
}

function newHome(){
    //Displays all parts of the home page
  $test=  <<<DELIMITER
    <form method = "post">
    <div class= "sorting">
    <input type="submit" name="sort" class="change" value="Sort">
    <select class="change" name=sortType>
            <option value="">Select Sort</option>
            <option value = "AtoZ">Sort by name Alphabetically A-Z </option>
            <option value = "ZtoA">Sort by name Alphabetically Z-A </option>
            <option value = "CPhi">Sort by cost price high to low </option>
            <option value = "CPlo">Sort by cost price low to high </option>
            <option value = "SPhi">Sort by sell price high to low </option>
            <option value = "SPlo">Sort by sell price low to high </option>
            <option value = "SChi">Sort stock count high to low </option>
            <option value = "SClo">Sort stock count low to high </option>
            <option value = "SBhi">Sort brand Alphabetically A-Z </option>
            <option value = "SBlo">Sort brand Alphabetically Z-A </option>
            <option value = "cathi">Sort  category Alphabetically A-Z </option>
            <option value = "catlo">Sort  category Alphabetically Z-A </option>
          </select>
          </div>
</form>
<table class="stocklist" style = "width: 70%; border: 1px solid black; margin-left:15%; margin-right:15%; border-radius: 20px;">


<th>Stock Name</th>
<th>Cost Price</th>
<th>Sell Price</th>
<th>Stock Count</th>
<th>Stock Brand</th>
<th>Stock Category</th>
<th></th>
<th></th>

DELIMITER;
 echo $test;
//sort button types of sorting
if(isset($_POST['sort'])){
$sortby = escape_string($_POST['sortType']);

if($sortby== 'AtoZ'){
    $query = query("SELECT * FROM stockitem ORDER BY stockName ASC");   
}
elseif($sortby== 'ZtoA'){
    $query = query("SELECT * FROM stockitem ORDER BY stockName DESC");
}
elseif($sortby== 'CPhi'){
    $query = query("SELECT * FROM stockitem ORDER BY costPrice DESC");
}
elseif($sortby== 'CPlo'){
    $query = query("SELECT * FROM stockitem ORDER BY costPrice ASC");
}
elseif($sortby== 'SPhi'){
    $query = query("SELECT * FROM stockitem ORDER BY sellPrice DESC");
}
elseif($sortby== 'SPlo'){
    $query = query("SELECT * FROM stockitem ORDER BY sellPrice ASC");
}
elseif($sortby== 'SChi'){
    $query = query("SELECT * FROM stockitem ORDER BY stockCount DESC");
}
elseif($sortby== 'SClo'){
    $query = query("SELECT * FROM stockitem ORDER BY stockCount ASC");
}
elseif($sortby== 'SBhi'){
    $query = query("SELECT * FROM stockitem ORDER BY brand ASC");
}
elseif($sortby== 'SBlo'){
    $query = query("SELECT * FROM stockitem ORDER BY brand DESC");
}
elseif($sortby== 'cathi'){
    $query = query("SELECT * FROM stockitem ORDER BY category ASC");
}
elseif($sortby== 'catlo'){
    $query = query("SELECT * FROM stockitem ORDER BY category DESC");
}
else{
    $query =  query("SELECT * FROM stockitem");
}

}
else{
    $query =  query("SELECT * FROM stockitem");
}

confirm($query);
//fetches data from the database to show the different items
    while ($row = fetch_array($query)) {
$stock = <<<DELIMITER
<tr>
<td>{$row['stockName']}</td>
<td>R{$row['costPrice']}</td>
<td>R{$row['sellPrice']}</td>
<td>{$row['stockCount']}</td>
<td>{$row['brand']}</td>
<td>{$row['category']}</td>
<div class = "buttons">
<td><button class = "change" type="button"><a href= "item.php?id={$row['stockID']}" > View</a></button></td>
<td><button class = "change" type="button"><a href= "deletestock.php?id={$row['stockID']}" > Remove</a></button></td>
</div>
</tr>

DELIMITER;
echo $stock;
    }
}
function getItem(){
    
    //Shows specific item details on their specific page
            
    $query =  query("SELECT * FROM stockitem WHERE stockID = " . escape_string($_GET['id']) . " ");

    confirm($query);

    while ($row = fetch_array($query)){

$item = <<<DELIMITER
<div class= "addstuff addname">
<h1>{$row['stockName']}</h1>
</div>
<div class = "item">
<ul>

<b>
<div class = "space">
<li>
<p><h4>Description:</h4> </p><p class = "desc"> {$row['description']}</p>
</li>
</div>
<div class = "space">
<li>
<p>Brand: {$row['brand']}</p>
</li>
</div>

<div class = "space">
<li>
<p>Cost Price: R{$row['costPrice']}</p>
</li>
</div>

<div class = "space">
<li>
<p>Sell Price: R{$row['sellPrice']}</p>
</li>
</div>
<div class = "space">
<li>
<p>Stock Available: {$row['stockCount']}</p>
</li>
</div>
</b>
</ul>
<button class = "change" type="button"><a href= "edit.php?id={$row['stockID']}" > Edit</a></button>
<td><button class = "change" type="button"><a href= "deletestock.php?id={$row['stockID']}" > Remove</a></button></td>



DELIMITER;

echo $item;
    }
}

function getBrand(){
    //retrieves brand info from the database and displays it
    $query =  query("SELECT * FROM brand");

    confirm($query);

    while ($row = fetch_array($query)) {

$brand = <<<DELIMITER


                
                <tr>
                    <td>{$row['brandID']}</td>
                    <td>{$row['brandName']}</td>
                    <td><button class = "change" type="button"><a href= "deleteBrand.php?id={$row['brandID']}" > Remove</a></button></td>
                </tr>
         

DELIMITER;
echo $brand;

    }

}

function getCat(){
    //retrieves category info from the database and displays it
    $query =  query("SELECT * FROM categories");

    confirm($query);

    while ($row = fetch_array($query)) {

$cat = <<<DELIMITER


                
                <tr>
                    <td>{$row['catID']}</td>
                    <td>{$row['catName']}</td>
                    <td><button class = "change" type="button"><a href= "deleteCate.php?id={$row['catID']}" > Remove</a></button></td>
                    
                </tr>
         

DELIMITER;
echo $cat;

    }
}
function addProduct()
{//Adds a products to the database

    if (isset($_POST['publish'])) {

       

        $stockName = escape_string($_POST['stockName']);
        $stockCate = escape_string($_POST['stockCate']);
        $stocksellPrice = escape_string($_POST['sellPrice']);
        $stockcostPrice = escape_string($_POST['costPrice']);
        $stockDesc = escape_string($_POST['stockDesc']);
        $stockBrand = escape_string($_POST['stockBrand']);
        $stockCount = escape_string($_POST['stockCount']);
      //validation prevents empty data inputs  
        if(!isset($stockName) || trim($stockName) == ''||!isset($stockCount) || trim($stockCount) == ''||!isset($stockCate) || trim($stockCate) == ''||!isset($stocksellPrice) || trim($stocksellPrice) == ''||
        !isset($stockcostPrice) || trim($stockcostPrice) == ''||!isset($stockDesc) || trim($stockDesc) == ''||!isset($stockBrand) || trim($stockBrand) == ''||!isset($stockCount) || trim($stockCount) == '')
{
   echo "You did not fill out the required items.";
}
else{
        $query = query("INSERT INTO stockitem(stockName, category, costPrice,sellPrice, description, brand, stockCount) 
        VALUES ('{$stockName}', '{$stockCate}', '{$stockcostPrice}', '{$stocksellPrice}', '{$stockDesc}', '{$stockBrand}', '{$stockCount}')");
        confirm($query);
        setMessage("Added Product");
        redirect("Home.php");
}
    }
}

function showCat()
{//shows the different categories to the admin when adding a product
    $query = query("SELECT * FROM categories");
    confirm($query);

    while ($row = fetch_array($query)) {
        $categories = <<<DELIMITER

<option value ="{$row['catName']}">{$row['catName']}</option>

DELIMITER;
        echo $categories;
    }
}
function showBrand()
{//shows the different brands to the admin when adding a product
    $query = query("SELECT * FROM brand");
    confirm($query);

    while ($row = fetch_array($query)) {
        $brand = <<<DELIMITER

<option value ="{$row['brandName']}">{$row['brandName']}</option>

DELIMITER;
        echo $brand;
    }
}

function addBrand(){
    //adds a new brand to the database
    if (isset($_POST['addBrand'])) {

//validation prevents empty data inputs
        $brandName = escape_string($_POST['brandName']);
        if (empty($brandName) || $brandName == " ") {
            setMessage("Cannot insert blank brand.");
        } else {
            $query = query("INSERT INTO brand(brandName) VALUES ('{$brandName}')");
            confirm($query);
            setMessage("Brand Added.");
            redirect("brand.php");
        }
    }
}
function addCat(){
    //adds a new category to the database
    if (isset($_POST['addCat'])) {

//validation prevents empty data inputs
        $catname = escape_string($_POST['catName']);
        if (empty($catname) || $catname == " ") {
            setMessage("Cannot insert blank category.");
        } else {
            $query = query("INSERT INTO categories(catName) VALUES ('{$catname}')");
            confirm($query);
            setMessage("Category Added.");
            redirect("categories.php");
        }
    }
}


function editStock(){
    //used to edit the items in the database
    if (isset($_POST['publish'])) {
 
       

        $stockName = escape_string($_POST['stockName']);
        $stockCate = escape_string($_POST['cate']);
        $stocksellPrice = escape_string($_POST['sellPrice']);
        $stockcostPrice = escape_string($_POST['costPrice']);
        $stockDesc = escape_string($_POST['stockDesc']);
        $stockBrand = escape_string($_POST['brand']);
        $stockCount = escape_string($_POST['stockCount']);
        //validation prevents empty data inputs
        
        if(!isset($stockName) || trim($stockName) == ''||!isset($stockCount) || trim($stockCount) == ''||!isset($stockCate) || trim($stockCate) == ''||!isset($stocksellPrice) || trim($stocksellPrice) == ''||
        !isset($stockcostPrice) || trim($stockcostPrice) == ''||!isset($stockDesc) || trim($stockDesc) == ''||!isset($stockBrand) || trim($stockBrand) == ''||!isset($stockCount) || trim($stockCount) == '')
{
   echo "You did not fill out the required items.";
}
else{
        $query = query("UPDATE stockitem SET stockName = '$stockName', category = '$stockCate', costPrice = '$stockcostPrice',sellPrice = '$stocksellPrice', description = '$stockDesc', 
        brand = '$stockBrand', stockCount = '$stockCount' WHERE stockID =" . escape_string($_GET['id'])." ");
        
        confirm($query);
        setMessage("Edited Product");
        redirect("item.php?id=" . escape_string($_GET['id'])." ");
}
    }
}

function search(){
    //searches the database for specific items
    if (isset($_POST['search'])){
        $search= $_POST['search'];
    
    $getsearch = query("SELECT * FROM stockitem WHERE stockName LIKE '%$search%'");
    confirm($getsearch);

   if(mysqli_num_rows($getsearch) > 0){ 
    while($row = fetch_array($getsearch)){
        
    $results=    <<<DELIMITER
    

<tr>
        <td>{$row['stockName']}</td>
        <td>{$row['costPrice']}</td>
        <td>{$row['sellPrice']}</td>
        <td>{$row['stockCount']}</td>
        <td><button class = "change" type="button"><a href= "item.php?id={$row['stockID']}" > View</a></button></td>
<tr>


DELIMITER;
echo $results;
    }
    }else{
        $results = <<<DELIMITER
<h2>No results found... </h2>
DELIMITER;
echo $results;
    }
    }
    
}


?>