<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/online_store/core/init.php';
if(!is_logged_in())
{
	login_error_redirect();
}

include 'includes/header.php';
include 'includes/navigation.php';
//delete
if(isset($_GET['delete']))
{
	$id = sanitize($_GET['delete']);
	$db->query("UPDATE products SET deleted = 1 WHERE id = '$id'");
	header('Location: products.php');
}
if(isset($_GET['add']) || isset($_GET['edit'])){
    $brandQuery = $db->query("SELECT * FROM brand ORDER BY brand");
    $parentQuery = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY categories");
	$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
	$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
	$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
	$categories = ((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):'');
	$price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
	$list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):'');
	$description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');
	$sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):'');
	$sizes = rtrim($sizes,',');
	$saved_image = '';
	if(isset($_GET['edit']))
	{
		$edit_id = (int)$_GET['edit'];
		$product_result = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
		$product = mysqli_fetch_assoc($product_result);
		if(isset($_GET['delete_image'])){
			$image_url = $_SERVER['DOCUMENT_ROOT'].$product['image'];
			unlink($image_url);
			$db->query("UPDATE products SET image = '' WHERE id = '$edit_id'");
			header('Location: products.php?edit='.$edit_id);
		}
		$categories =((isset($_POST['child']) && ($_POST['child']) != '')?sanitize($_POST['child']):$product['categories']);
		$title = ((isset($_POST['title']) && ($_POST['title']) != '')?sanitize($_POST['title']):$product['title']);
		$brand = ((isset($_POST['brand']) && ($_POST['brand']) != '')?sanitize($_POST['brand']):$product['brand']);
                
		$parentQ = $db-> query("SELECT * FROM categories WHERE id = '$categories'");
		$parentResults = mysqli_fetch_assoc($parentQ);
		
		$parent = ((isset($_POST['parent']) && ($_POST['parent']) != '')?sanitize($_POST['parent']):$parentResults['parent']);
		$price = ((isset($_POST['price']) && ($_POST['price']) != '')?sanitize($_POST['price']):$product['price']);
		$list_price = ((isset($_POST['list_price']) && ($_POST['list_price']) != '')?sanitize($_POST['list_price']):$product['list_price']);
		$description = ((isset($_POST['description']) && ($_POST['description']) != '')?sanitize($_POST['description']):$product['description']);
		$sizes = ((isset($_POST['sizes']) && ($_POST['sizes']) != '')?sanitize($_POST['sizes']):$product['sizes']);
		$sizes = rtrim($sizes,',');
		$saved_image = (($product['image'] != '')?$product['image']:'');
		$dbPath = $saved_image;
	}
	if(!empty($sizes))
        {
            $sizesString = sanitize($sizes);
            $sizesString = rtrim($sizesString,','); 
            $sizesArray = explode(',', $sizesString);
            $sarray = array();
            $qarray = array();
            foreach ($sizesArray as $ss){
                $s = explode(':',$ss);
                $sarray[] = $s[0];
                $qarray[] = $s[1];
            }
        }
        else {
              $sizesArray = array();    
            }
    $sizesArray = array();
    if($_POST)
    {
        
        $errors = array();
        
        
            $required = array('title','price','parent','child','sizes');
            foreach ($required as $field){
                if($_POST[$field] == '')
                {
                    $errors[] = 'all fiels with an astrisk are required';
                    break;
                }
            }
             if(!empty($_FILES))
           {
                 var_dump($_FILES);
                 $photo = $_FILES['photo'];
                 $name = $photo['name'];
                 $nameArray = explode('.', $name);
                 $fileName = $nameArray[0];
                 $fileExt = $nameArray[1];
                 $mime = explode('/', $photo['type']);
                 $mimeType = $mime[0];
                 $mimeExt = $mime[1];
                 $tmpLoc = $photo['tmp_name'];
                 $fileSize = $photo['size'];
                 $allowed = array('png','jpg','jpeg','gif','PNG','JPG','JPEG','GIF');
                
                 $uploadName = md5(microtime()).'.'.$fileExt;
                  $uploadPath = BASEURL. 'products/'.$uploadName;
                 $dbPath = '/online_store/products/'.$uploadName;
               if($mimeType != 'image')
               {
                   $errors [] = 'The file is not a image';
               }
               if(!in_array($fileExt, $allowed))
               {
                   $errors[] = 'The photo extension not allowed';
               }
               if($fileSize > 15000000)
               {
                   $errors [] = 'the file must be less than 15mb';
               }
              /* if($fileExt != $mimeExt && ($mimeExt == 'jpeg') && $mimeExt != 'jpg')
               {
                   $errors[] = 'Inavlid file extension';
               }*/
              }
              
       if(!empty($errors))
       {
           echo display_errors($errors);
       }
       else{
           // update db
		   if(!empty($_FILES)){
           move_uploaded_file($tmpLoc, $uploadPath);
		   }
           $insertSql = "INSERT INTO products (`title`, `price`, `list_price`, `brand`, `categories`, `image`,`size`,`description`) VALUES ('$title','$price','$list_price','$brand','$categories','$dbPath','$sizes','$description')";
		   if(isset($_GET['edit']))
		   {
			   $insertSql = "UPDATE products SET title = '$title', price = '$price', list_price = '$list_price', brand= '$brand', categories = '$categories', sizes = '$sizes', description = '$description', image = '$dbpath' WHERE id = '$edit_id'";
		   }
           $db->query($insertSql);
           header('Location: products.php');
       }
      
    }
 ?> 
<h2 class="text-center"><?= ((isset($_GET['edit']))?'Edit ':'Add a ');?>products</h2><hr>
<form action="products.php?<?= ((isset($_GET['edit']))?'edit='.$edit_id:'add=1'); ?>" method="post" enctype="multipart/form-data">
    <div class="form-group col-md-3">
        <label for="title">Title*:</label>
        <input type="text" name="title" class="form-control" id="title" value="<?= $title; ?>">
    </div>
    <div class="form-group col-md-3">
        <label for="brand">Brand*:</label>
        <select class="form-control" id="brand" name="brand">
            <option value=""<?= (($brand == '')?' selected':''); ?>></option>
             <?php while($b= mysqli_fetch_assoc($brandQuery)): ?>
            <option value="<?=$b['id'];?>"<?= (($brand == $b['id'])?' selected':''); ?>><?= $b['brand']; ?></option>
            <?php endwhile; ?>
        </select>
    </div> 
    <div class="form-group col-md-3">
        <label for="brand">Parent Category*:</label>
        <select class="form-control" id="parent" name="parent">
            <option value=""<?= (($parent == '')?' selected':''); ?>></option>
             <?php while($p = mysqli_fetch_assoc($parentQuery)): ?>
            <option value="<?=$p['id'];?>"<?= (($parent == $p['id'])?' selected':''); ?>><?= $p['categories']; ?></option>
            <?php endwhile; ?>
        </select>
    </div> 
    <div class="form-group col-md-3">
        <label for="child">Child Category*:</label>
        <select id="child" name="child" class="form-control"></select>
    </div>
    <div class="form-group col-md-3">
        <label for="child">Price*:</label>
        <input type="text" id="price" name="price" class="form-control" value="<?= $price;?>">
    </div>
    <div class="form-group col-md-3">
        <label for="child">List Price</label>
        <input type="text" id="list_price" name="list_price" class="form-control" value="<?= $list_price;?>">
    </div>
    <div class="form-group col-md-3">
        <label>Quantity & Sizes*:</label>
        <button class="btn btn-default form-control"  onclick="jQuery('#sizesModal').modal('toggle');return false;">Quantity & Size</button>
    </div>
    <div class="form-group col-md-3">
        <label for="sizes">Sizes $ Quantity</label>
        <input type="text" class="form-control" id="sizes" name="sizes" value="<?=$sizes;?>"readonly="">
    </div>
    <div class="form-group col-md-6">
	<?php if($saved_image != ''): ?>
	<div class="saved_image">
	<img src="<?= $saved_image; ?>" alt="saved_image">
	<a href="products.php?delete_image=1&edit=<?=$edit_id;?>" class="text-danger">Delete Image</a>
	</div>
	<?php else :?>
        <label for="photo">Product Photo</label>
        <input type="file" name="photo" id="photo" class="form-control">
		<?php endif;?>
    </div>
     <div class="form-group col-md-6">
        <label for="description">Description</label>
        <textarea id="description" name="description" class="form-control" rows="6"><?=$description;?></textarea>
    </div>
    <div class="form-group pull-right">
	    
		<a href="products.php" class="btn btn-default">Cancel</a>
        <input type="submit" value="<?=((isset($_GET['edit']))?'Edit ':'Add a ');?> Product" class="btn btn-success ">
    </div>
    <div class="clearfix"></div>
 </form>
    <!-- Modal -->
<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sizesModalLabel">Size & Quantity</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="container-fluid">
        <?php for($i=1;$i <=12;$i++) : ?>
          <div class="form-group col-md-4">
              <label for="size<?=$i;?>">Size</label>
              <input type="text" name="size<?=$i;?>" id="size<?=$i;?>" value="<?= ((!empty($sarray[$i-1]))?$sarray[$i-1]:'') ?>" class="form-control">
          </div>
          <div class="form-group col-md-2">
              <label for="qty<?=$i;?>">Quantity:</label>
              <input type="number" name="qty<?=$i;?>" id="qty<?=$i;?>" value="<?= ((!empty($qarray[$i-1]))?$qarray[$i-1]:'') ?>" min="0" class="form-control">
          </div>
        <?php endfor; ?>
       </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return false;">Save changes</button>
      </div>
    </div>
  </div>
</div>
<?php
}else{
$sql = "SELECT * FROM products WHERE deleted = 0";
$presult = $db->query($sql);

if(isset($_GET['feature']))
{
    $id = (int)$_GET['id'];
    $featured = (int)$_GET['feature'];
    $featuresql = "UPDATE products SET feature = '$featured' WHERE id='$id'";
    $db->query($featuresql);
    header('Location: products.php');
}
?>
<h2 class="text-center">Products</h2>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add Product</a><div class="clearfix"></div>

<table class="table table-bordered table-condensed table-striped">
    <thead>
    <th></th><th>Products</th><th>Price</th><th>Category</th><th>Featured</th><th>Sold</th>
    </thead>
    <tbody>
        <?php while($product = mysqli_fetch_assoc($presult)) :
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
                <a href="products.php?edit=<?= $product['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                <a href="products.php?delete=<?= $product['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
            </td>
            <td><?= $product['title']; ?></td>
            <td><?= money($product['price']); ?></td>
            <td><?= $category; ?></td>
            <td><a href="products.php?feature=<?= (($product['feature'] == 0)?'1':'0') ?>&id=<?= $product['id'];?>" class="btn btn-xs btn-default">
              <span class="glyphicon glyphicon-<?= (($product['feature'] == 1)?'minus':'plus'); ?>"></span> 
                </a>&nbsp <?= (($product['feature'] == 1)?'Featured product':''); ?></td>
              
            <td>0</td>
        </tr>
        <?php endwhile; ?>
    </tbody>    
</table>

<?php }
include 'includes/footer.php';
?>
<script>
jQuery('document').ready(function()
{
	get_child_options(<?= $categories; ?>);
	
});
</script>
