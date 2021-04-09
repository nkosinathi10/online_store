<?php
require_once 'core/init.php';
$charge_id;
$full_name = sanitize($_POST['full_name']);
$email = ($_POST['email']);
$street = sanitize($_POST['street']);
$street2 = sanitize($_POST['street']);
$city = sanitize($_POST['city']);
$state = sanitize($_POST['state']);
$zip_code = sanitize($_POST['zip']);
$country = sanitize($_POST['country']);
$tax = sanitize($_POST['tax']);
$sub_total = sanitize($_POST['grand_total']);
$grand_total = sanitize($_POST['grand_total']);
$cat_id = sanitize($_POST['cart_id']);
$description = sanitize($_POST['description']);
$charge_amount = number_format($grand_total,2) * 100;
$metadata = array(
    "cart_id" => $cat_id,
    "tax"  => $tax,
    "sub_total" => $sub_total,
);
$db -> query("INSERT INTO transactions "
        . "(charge_id,cart_id,full_name,email,street,street2,city,state,zip_code,country,tax,sub_total,grand_total,description,txn_type) "
        . "VALUES "
        . "('$cart_id','$cart_id','$full_name','$email','$street','$street2','$city','$state','$zip_code','$country','$tax','$sub_total','$grand_total','$description','yyyy')");
$db -> query("UPDATE cart SET paid = 1 WHERE id = '{$cart_id}'");
$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
setcookie(CART_COOKIE,'',1,"/",$domain,false);

include 'includes/head.php';
include 'includes/navigation.php';
include 'includes/headerpartial.php';
?>
<h1 class="text-center text-success">Thank you</h1>
<p>Your card has been successfuly charged <?= money($grand_total); ?></p>