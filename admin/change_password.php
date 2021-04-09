<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/online_store/core/init.php';
if(!is_logged_in())
{
	login_error_redirect();
}
include 'includes/header.php';

$hashed = $user_data['password'];
$old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
$old_password = trim($old_password);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$confirm = trim($confirm);
$new_hashed = password_hash($password,PASSWORD_DEFAULT);
$user_id = $user_data['id'];
$errors = array();
?>

<div id = "login-form">
   <div >
   <?php
   if($_POST)
   {
	   if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm']))
	   {
		   $errors[] = 'you must fill all spaces';
	   }
	   
	   
	   //checck password
	   if(strlen($password)< 6)
	   {
		   $errors[] = "password must be atleast 6 characters.";
	   }
	   
       if($password != $confirm)
	   {
		   $errors[] = 'password do not match';
	   }
	   
           if(!password_verify($old_password,$hashed))
	   {
		   $errors[] ="wrong old password.";
	   }
	   
	   if(!empty($errors))
	   {
		   echo display_errors($errors);
	   }
           else{
		   //change pass
		   $db->query("UPDATE users SET password = '$new_hashed' WHERE id = '$user_id'");
		   $_SESSION['success_flash'] = 'your password has been updated.';
		    header('Location : login.php');

	   }
	  
   }
   ?>
   </div>
    <h2 class="text-center">Change password</h2>
      <form action="change_password.php" method="post">
         <div class="form-group">
          <label for="old_password">Old password:</label>
          <input type="password" name="old_password" id="old_password" class="form-control" value="<?= $old_password;?>" >
         </div>
         <div class="form-group">
           <label for="password">New Password:</label>
           <input type="password" name="password" id="password" class="form-control" value="<?= $password;?>" >
         </div>
		  <div class="form-group">
           <label for="confirm">Confirm Password:</label>
           <input type="password" name="confirm" id="confirm" class="form-control" value="<?= $confirm;?>" >
         </div>
		 <div class="form-group">
		  <a href="index.php" class="btn btn-default">Cancel</a>
		  <input type="submit" value="change" class="btn btn-primary">
		 </div>
       </form>
	 <p class="text-right"><a href="/online_store/index.php" alt="home">Visit site</a></p>
</div>

<?php 
include 'includes/footer.php';
