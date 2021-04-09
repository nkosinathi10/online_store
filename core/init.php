<?php
$db = mysqli_connect('localhost:3307','root','','online_store');
if(mysqli_connect_errno())
{
echo 'database connection failed with following error '.mysqli_connect_error();
    die();
}
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/online_store/config.php';
require_once BASEURL.'helpers/helpers.php';

$cart_id = '';
if(isset($_COOKIE[CART_COOKIE])){
	$cart_id = sanitize($_COOKIE[CART_COOKIE]);
}

if(isset($_SESSION['SBuser']))
{
	$user_id = $_SESSION['SBuser'];
	$query = $db->query("SELECT * FROM users WHERE id ='$user_id'");
	$user_data = mysqli_fetch_assoc($query);
	
}

if(isset($_SESSION['success_flash']))
{
	echo '<div class="bg-success"><p class="text-success text-center">'.$_SESSION['success_flash'].'</p></div>';
	unset($_SESSION['success_flash']);
}
if(isset($_SESSION['success_flash']))
{
	echo '<div class="bg-danger"><p class="text-danger text-center">'.$_SESSION['error_flash'].'</p></div>';
	unset($_SESSION['error_flash']);
}

?>

