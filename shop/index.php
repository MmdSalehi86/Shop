<?php

session_start();

include "lib/db.php";/*
$db = new DB();
$row = $db->sel_user_with_mobile_or_email('0913', '')->fetch_assoc();
$_SESSION = $row;*/

if (isset($_GET['pr_id']))
    setcookie('pr_id', $_GET['pr_id'], time() + 1800, '/', 'localhost');

if (!isset($_SESSION['id']) or $_SESSION['access'] == 0) {
    //  go to login page
    header('location: auth/login.php');

} else {
    //  go to admin page
    header(__LOC__ . __D_ADMIN__ . __ADMIN__ );
}
