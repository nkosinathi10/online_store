<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/online_store/core/init.php';
if(!is_logged_in())
{
	login_error_redirect();
}

include 'includes/header.php';
include 'includes/navigation.php';
$sql = "SELECT * FROM categories WHERE parent = 0";
$result = $db->query($sql);
$errors = array();
$category = '';
$parebt_value = 0;
$post_parent = '';
//delete category
if(isset($_GET['delete']) && !empty($_GET['delete']))
{
   $delete_id = (int)$_GET['delete'];
   $delete_id = sanitize($delete_id);
   $csql = "SELECT * FROM categorie WHERE id = '$delete_id'";
   $cresults = $db->query($csql);
   $category = mysqli_fetch_assoc($cresult);
   if($categories['parent'] == 0)
   {
    $dsql = "DELETE FROM categories WHERE parent ='$delete_id'";   
    $db->query($dsql);
    
   }
   $dsql = "DELETE FROM categories WHERE id ='$delete_id'";
   $db->query($dsql);
   header('Location: categories.php');
}
//edit category
if(isset($_GET['edit']) && !empty($_GET['edit']))
{
    $edit_id = (int)$_GET['edit'];
    $edit_id = sanitize($edit_id);
    $edit_sql = "SELECT * FROM categories WHERE id ='$edit_id'";
    $edit_result = $db->query($edit_sql);
    $edit_category = mysqli_fetch_assoc($edit_result);
    
}
//process form
if(isset($_POST)&&!empty($_POST))
{
    $post_parent = sanitize($_POST['parent']);
    $category = sanitize($_POST['category']);
    $sqlform = "SELECT * FROM categories WHERE categories = '$category' AND parent = '$post_parent'";
    if(isset($_GET['edit']))
    {
        $id = $edit_category['id'];
        $sqlform = "SELECT * FROM categories WHERE categories = '$category' AND parent = '$post_parent' AND id != '$id'";
    }
    $fresult = $db->query($sqlform);
    $count = mysqli_num_rows($fresult);
    
    //if categories is blank
    if($category == '')
    {
       $errors [] .= 'the category can not be blank.';   
    }
    //if exist
    if($count > 0)
    {
        $errors [] .= $category. 'already exist choose new category';
    }
    //display errors and update db
    if(!empty($errors))
    {
        $display = display_errors($errors);
        ?>
     <script>
        jQuery('document').ready(function(){
        jQuery('#error').html('<?= $display;?>');
        
    });  
    
       </script>
    <?php } else {
    //update database
        $updatesql = "INSERT INTO categories (categories, parent) VALUES ('$category','$post_parent')";
        if(isset($_GET['edit']))
        {
            $updatesql = "UPDATE categories SET categories = '$category', parent='$post_parent' WHERE id='$edit_id'";
        }
        $db->query($updatesql);
        header('Location: categories.php');
    }
}
$category_value = '';
if(isset($_GET['edit']))
{
    $category_value = $edit_category['categories'];
    $parent_value = $edit_category['parent'];
}else{
    if(isset($_POST))
    {
        $category_value = $category;
        $parent_value = $post_parent;
    }
}
?>
<h2 class="text-center" style="color: blueviolet">Categories</h2><hr>
<!-- form -->
<div class="col-md-6">
    <form class="form" action="categories.php<?= (isset($_GET['edit']))?'?edit='.$edit_id:'' ?>" method="post">
        <legend><?=( (isset($_GET['edit']))?'Edit':'Add a' )?> category</legend>
        <div id="error"></div>
        <div class="form-group">
            <label for="parent">Parent</label>
            <select class="form-control" name="parent" id="parent">
                <option value="0"><?= (($parebt_value == 0)?'':'Parent'); ?></option>
                <?php while ($parent = mysqli_fetch_assoc($result)) : ?>
                <option value="<?=$parent['id']?>"<?= (($parent_value == $parent['id'])?' selected="selected"':'') ?> ><?=$parent['categories']?></option>
                    <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" class="form-control" id="category" name="category" value="<?= $category_value;?>">
        </div>
        <div class="form-group">
            <input type="submit" value="<?=(isset($_GET['edit'])?'Edit':'Add');?> Category" class="btn btn-success">
        </div>
    </form>
</div>
<!-- category table -->
<div class="col-md-6">
    <table class="table table-bordered">
        <thead>
        <th>Category</th><th>Parent</th><th></th>
        </thead>
        <tbody>
            <?php 
            $sql = "SELECT * FROM categories WHERE parent = 0";
            $result = $db->query($sql);
            while($parent = mysqli_fetch_assoc($result)) :
                $parent_id = (int)$parent['id'];
             $sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
             $cresult = $db->query($sql2);
                ?>
            <tr class="bg-primary">
                <td><?= $parent['categories']; ?></td>
                <td>Parent</td>
                <td>
                    <a href="categories.php?edit=<?= $parent['id']; ?>" class="btn btn-xs btn-default"  ><span class="glyphicon glyphicon-pencil"></span></a>
                    <a href="categories.php?delete=<?= $parent['id']; ?>" class="btn btn-xs btn-default"  ><span class="glyphicon glyphicon-remove"></span></a>
                    
                </td>
            </tr>
            <?php while($child = mysqli_fetch_assoc($cresult)) : ?>
            <tr class="bg-info">
                <td><?= $child['categories']; ?></td>
                <td><?= $parent['categories'];?></td>
                <td>
                    <a href="categories.php?edit=<?= $child['id']; ?>" class="btn btn-xs btn-default"  ><span class="glyphicon glyphicon-pencil"></span></a>
                    <a href="categories.php?delete=<?= $child['id']; ?>" class="btn btn-xs btn-default"  ><span class="glyphicon glyphicon-remove"></span></a>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php  endwhile; ?>
        </tbody>
    </table>
</div>
<hr>

<?php include 'includes/footer.php'; ?>
