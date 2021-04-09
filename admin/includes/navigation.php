
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
      <a href="/admin/index.php" class="navbar-brand">Nathi online shop</a>
    <ul class="nav navbar-nav">
        <li><a href="brands.php">Brands</a></li>
        <li><a href="categories.php">Categories</a></li>
        <li><a href="products.php">Products</a></li>
         <li><a href="archive.php">Archive</a></li>
		 <?php if(has_permission('admin')):?>
		 <li><a href="users.php">Users</a></li>
		 <?php endif;?>
		 <li class="dropdown">
		 <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello <?= $user_data['name'];?>
		 <span class="caret"></span>
		 </a>
		   <ul class="dropdown-menu" role="menu">
		    <li><a href="change_password.php">Change Password</a></li>
			<li><a href="logout.php">Logout</a></li>
		 </li>
		 
    <!-- <li class="dropdown">
         <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['categories'];?><span class="caret"></span></a>
      <ul class="dropdown-menu" role="menu">
        <li><a href="#"></a></li>
      </ul>
     </li>-->
   
    </ul>
  </div>
 </nav>
