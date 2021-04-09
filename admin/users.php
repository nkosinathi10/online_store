<?php
require_once '../core/init.php';
if(!is_logged_in())
{
	login_error_redirect();
}
if(!has_permission('admin'))
{
	permission_redirect('index.php');
}


include 'includes/header.php';
include 'includes/navigation.php';
if(isset($_GET['delete']))
{
	$delete_id = sanitize($_GET['delete']);
	$db->query("DELETE FROM users WHERE id = '$delete_id'");
	$_SESSION['success_flash'] = 'User has been deleted!';
	header('Location : users.php');
}
if(isset($_GET['add']))
{
       
	$name = ((isset($_POST['name']))? sanitize($_POST['name']):'');
	$email = ((isset($_POST['email']))? sanitize($_POST['email']):'');
	$password = ((isset($_POST['password']))? sanitize($_POST['password']):'');
	$confirm = ((isset($_POST['confirm']))? sanitize($_POST['confirm']):'');
	$permissions = ((isset($_POST['permissions']))? sanitize($_POST['permissions']):'');
	$number = ((isset($_POST['number']))? sanitize($_POST['number']):'');
	$surname = ((isset($_POST['surname']))? sanitize($_POST['surname']):'');
	$username = ((isset($_POST['username']))? sanitize($_POST['username']):'');
	
        $errors = array();
	if($_POST)
	{
            $emailQuery = $db->query("SELECT * FROM user WHERE email = '$email'");
                $emailCount = mysqli_num_rows($emailQuery);
        if($emailCount != 0)
	{
		$errors [] = 'Email already exist in our database';
	}
		$required = array('name','surname','number','username','password','email','confirm','permissions');
		foreach($required as $f)
		{
			if(empty($_POST[$f]))
			{
				$errors[] = 'you need to fill all fields';
				break;
			}
		}
		if(strlen($password) < 6 )
		{
			$errors[] = 'Password must be atleast 6 characters'; 
		}
		if($password != $confirm){
			$errors[] = 'password do not match';
		}
		if(!filter_var($email,FILTER_VALIDATE_EMAIL))
		{
			$errors[] = 'You must enter a valid email';
		}
		
		if(!empty($errors))
		{
			echo display_errors($errors);
		}
		else{
			//add to database
			$hashed = password_hash($password,PASSWORD_DEFAULT);
                     
			$db->query("INSERT INTO users (name,surname,username,cell,password,permissions,email) VALUES ('$name','$surname','$username','$number','$hashed','$permissions','$email')");
			$_SESSION['success_flash'] = 'user has been added';
			header('Location: users.php');
		}
	}
	?>
	<h2 class="text-center">Add new user</h2><hr>
	<form action="users.php?add=1" method="post">
	  <div class="form-group col-md-6">
	    <label for="name">Full name:</label>
		<input type="text" name="name" id="name" class="form-control" value="<?=$name;?>">
	  </div>
	  <div class="form-group col-md-6">
	    <label for="name">Last name:</label>
		<input type="text" name="surname" id="surname" class="form-control" value="<?=$surname;?>">
	  </div>
	  <div class="form-group col-md-6">
	    <label for="name">Email:</label>
		<input type="email" name="email" id="email" class="form-control" value="<?=$email;?>">
	  </div>
	  <div class="form-group col-md-6">
	    <label for="name">Cell number:</label>
		<input type="text" name="number" id="number" class="form-control" value="<?=$number;?>">
	  </div>
	  <div class="form-group col-md-6">
	    <label for="name">Username:</label>
		<input type="text" name="username" id="username" class="form-control" value="<?=$username;?>">
	  </div>
	  <div class="form-group col-md-6">
	    <label for="name">Password:</label>
		<input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
	  </div>
	  <div class="form-group col-md-6">
	    <label for="name">Confirm Password:</label>
		<input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
	  </div>
	  <div class="form-group col-md-6">
	    <label for="name">Permissions:</label>
		<select class="form-control" name="permissions" >
		 <option value=""<?= (($permissions == '')?' selected':'');?>></option>
		 <option value="editor"<?= (($permissions == 'editor')?' selected':'');?>>Editor</option>
		 <option value="admin,editor"<?= (($permissions == 'admin,editor')?' selected':'');?>>Admin</option>
		</select>
	  </div>
         <div class="form-group text-right">
	   <a href="users.php" class="btn btn-default">Cancel</a>
	   <input type="submit" value="Add User" class="btn btn-primary">
	  </div>
 	</form>
	<?php
}
else
{

$userQuery = $db->query("SELECT * FROM users ORDER BY name");
?>

<h2>Users</h2>

<a href="users.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add new user</a><hr>
<table class="table table-bordered table-striped table-condensed">
 <thead><th></th><th>Name</th><th>Email</th><th>Join date</th><th>Last login</th><th>Permissions</th></thead>
 <tbody>
 <?php while($user = mysqli_fetch_assoc($userQuery)): ?>
   <tr>
    <td>
	<?php if($user['id'] != $user_data['id']):?>
	<a href="users.php?delete=<?= $user['id'];?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove"></span></a>
	<?php endif; ?>
	</td>
    <td><?= $user['name'];?></td>
	<td><?= $user['surname'];?></td>
	<td><?= pretty_date($user['join_date'])?></td>
	<td><?=(($user['last_login'] == '0000-00-00 00:00:00')?'Never':pretty_date($user['last_login']));?></td>
	<td><?= $user['permissions'];?></td>
	<?php endwhile; ?>
   </tr>
 </tbody>
</table>

<?php
}
?>
<?php

include 'includes/footer.php';
?>