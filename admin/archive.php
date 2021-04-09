<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/online_store/core/init.php';
if(!is_logged_in())
{
	login_error_redirect();
}

include 'includes/header.php';
include 'includes/navigation.php';
?>
<?php $sql = "SELECT * FROM products WHERE deleted = 1";
          $presult = $db->query($sql);
          if(isset($_GET['restore']))
		   {
                           $restore = (int)$_GET['restore'];
			   $insertSql = "UPDATE products SET deleted = 0 WHERE id = '$restore'";
                           $db->query($insertSql);
                           header('Location: archive.php');
		   }
         ?>
<h2 class="text-center">Archive</h2>
<table class="table table-bordered table-condensed table-striped">
    <thead>
        
    <th></th><th>Product</th><th>Price</th><th>Category</th><th>Sold</th>
        
    </thead>
    <tbody>
        <?php 
        
        while($product = mysqli_fetch_assoc($presult)) :
             $childID = $product['categories'];
             $calSql = "SELECT * FROM categories WHERE id = '$childID'";
             $result = $db->query($calSql);
             $child = mysqli_fetch_assoc($result);
             $parentID = $child['parent'];
             $pSql = "SELECT * FROM categories WHERE id = '$parentID'";
             $rparent = $db->query($pSql);
             $parent = mysqli_fetch_assoc($rparent);
             $category = $parent['categories'].'-'.$child['categories'];
        ?>
        <tr>
              <td>
                  <a href="archive.php?restore=<?= $product['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-refresh"></span></a>
            </td>
            <td><?= $product['title']; ?></td>
            <td><?= money($product['price']); ?></td>
            <td><?= $category; ?></td>
            <td>0</td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php 
include 'includes/footer.php';

