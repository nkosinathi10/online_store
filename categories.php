<?php
require_once 'core/init.php'; 
?>
<?php include 'includes/head.php';
      include 'includes/navigation.php';
       include 'includes/headerpartial.php';
       include 'includes/leftsidebar.php';
	   
	   if(isset($_GET['cat'])){
	$cat_id = sanitize($_GET['cat']);
}
else{
$cat_id = '';
}

 $sql = "SELECT * FROM products WHERE categories = '$cat_id'";
       $productQ = $db->query($sql);
	   $categories = get_category($cat_id);
       ?>
       
       <!--main content-->
 <div class="col-md-8">
     <h2 class="text-center"><?=$categories['parent'].' '.$categories['child'];?></h2>
     <div class="row">
         <?php while($product = mysqli_fetch_array($productQ)) : ?>
         
             <div class="col-md-3">
                 <h4><?php echo $product['title'];  ?></h4>
                 <img src="<?php echo $product['image'];  ?>" alt="<?php echo ''; $product['title'];  ?>"/>
             <p class="list-price text-danger">List price: R<s><?php echo $product['list_price'];  ?></s></p>
             <p class="price">Our price: R <?php echo $product['price'];  ?></p>
             <button type="button" class="btn btn-sm btn-success" onclick='detailsmodal(<?= $product['id']; ?>)'>
                 Details</button>
             </div>
         
            <?php
            endwhile;
            ?>
             
     </div>
 </div>
       <?php
       include 'includes/rightsidebar.php';
       include 'includes/footer.php';
       ?>
       
       





