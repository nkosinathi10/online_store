<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/online_store/core/init.php';
include 'includes/header.php';
$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$errors = array();
?>
<style>
body{
	background-image:url("/online_store/img/background.jpg");
	background-size: 100vw 100vh;
}
</style>
<div id = "login-form">
   <div >
   <?php
   if($_POST)
   {
	   if(empty($_POST['email']) || empty($_POST['password']))
	   {
		   $errors[] = 'you must provide email and password';
	   }
	   //validaTE email
	   if(!filter_var($email,FILTER_VALIDATE_EMAIL))
	   {
		   $errors[] = "you must enter a valid email";
	   }
	   
	   //checck password
	   if(strlen($password)< 6)
	   {
		   $errors[] = "password must be atleast 6 characters.";
	   }
	   
	   $query = $db->query("SELECT * FROM users WHERE email = '$email'");
	   $user = mysqli_fetch_assoc($query);
	   $userCount = mysqli_num_rows($query);
           if(!password_verify($password,$user['password']))
	   {
		   $errors [] ="wrong password.";
	   }
	   if($userCount < 1)
	   {
		   $errors [] = "User does not exist";
	   }
	   if(!empty($errors))
	   {
		   echo display_errors($errors);
	   }else{
		   // log user in
		   $user_id = $user['id'];
		   login($user_id);
	   }
	   
   }
   ?>
   </div>
    <h2 class="text-center">Login</h2>
      <form action="login.php" method="post">
         <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" name="email" id="email" class="form-control" value="<?= $email;?>" >
         </div>
         <div class="form-group">
           <label for="password">Password:</label>
           <input type="password" name="password" id="password" class="form-control" value="<?= $password;?>" >
         </div>
		 <div class="form-group">
		  <input type="submit" value="login" class="btn btn-primary>
		 </div>
       </form>
	 <p class="text-right"><a href="/online_store/index.php" alt="home">Visit site</a></p>
</div>

<?php 
include 'includes/footer.php';
