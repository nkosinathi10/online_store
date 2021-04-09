<?php
require_once 'core/init.php'; 
?>
<?php include 'includes/head.php';
 $sql = "SELECT * FROM products WHERE feature = 1";
       $feature=$db->query($sql);

      include 'includes/navigation.php';
       include 'includes/headerpic.php';
       include 'includes/leftsidebar.php';
       ?>
       
       <!--main content-->
 <div class="col-md-8">
     <h2 class="text-center">Featured products</h2>
     <div class="row">
         <?php while($product = mysqli_fetch_array($feature)) : ?>
         
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
       
       





