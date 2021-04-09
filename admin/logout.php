<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/online_store/core/init.php';
unset($_SESSION['SBuser']);
header('Location: login.php');