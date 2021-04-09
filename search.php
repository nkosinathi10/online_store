<?php
require_once 'core/init.php'; 
?>
<?php include 'includes/head.php';
      include 'includes/navigation.php';
       include 'includes/headerpartial.php';
       include 'includes/leftsidebar.php';
	   
       $sql = "SELECT * FROM products";
       $cat_id = (($_POST['cat'] != '')?sanitize($_POST['cat']):'');
       if($cat_id == ''){
           $sql .= ' WHERE deleted = 0';
       }
       else{
           $sql .= " WHERE categories = '{$cat_id}' AND deleted = 0";
       }
       $price_sort = (($_POST['price_sort'] != '')?sanitize($_POST['price_sort']):'');
       $min_price = (($_POST['min_price'] != '')?sanitize($_POST['min_price']):'');
       $max_price = (($_POST['max_price'] != '')?sanitize($_POST['max_price']):'');
       $brand = (($_POST['brand'] != '')?sanitize($_POST['brand']):'');
       if($min_price != '')
       {
           $sql .= " AND price >= '{$min_price}'";
       }
       if($max_price != '')
       {
           $sql .= " AND price <= '{$max_price}'";
       }
       if($brand != ''){
           $sql .= " AND brand = '{$brand}'";
       }
       if($price_sort == 'low'){
           $sql .= " ORDER BY price";
       }
       if($price_sort == 'high'){
           $sql .= " ORDER BY price DESC";
       }
       $productQ = $db->query($sql);
	   $categories = get_category($cat_id);
       ?>
       
       <!--main content-->
 <div class="col-md-8">
     <?php if($cat_id != ''):?>
     <h2 class="text-center"><?=$categories['parent'].' '.$categories['child'];?></h2>
     <?php else:?>
     <h2 class="text-center" ></h2>
     <?php endif;?>
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
       
       






