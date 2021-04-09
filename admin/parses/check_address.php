<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/online_store/core/init.php';
$name = sanitize($_POST['full_name']);
$email = sanitize($_POST['email']);
$street = sanitize($_POST['street']);
$street2 = sanitize($_POST['street2']);
$city = sanitize($_POST['city']);
$state = sanitize($_POST['state']);
$zip = sanitize($_POST['zip']);
$country = sanitize($_POST['country']);
$errors = array();
$required = array(
    'full_name' => 'Full_name',
    'email'     => 'Email',
    'street'    => 'Street_address',
    'city'      => 'City',
    'state'     => 'State',
    'zip'       => 'Zip',
    'country'   => 'Country',
);
//check if all fiedls are given
foreach ($required as $f => $d){
    if(empty($_POST[$f]) || $_POST[$f] == ''){
        $errors[] = ' all fields required.';
    }
    
}
if(!empty($errors)){
    echo display_errors($errors);
}else{
    echo "passed";
    }
