<?php
session_start();
include "../lib/check_admin.php";
include "../lib/db.php";

$db = new DB();

//  edit and delete user
if (isset($_GET['id']) and !empty($_GET['id'])) {
    $id = $_GET['id'];   // get id for edit user
    if (isset($_GET['del'])) {
        if ($_SESSION['id'] == $id) {
            echo '<h1>ERROR: You cannot delete yourself</h1><br/>';
            $location = __D_ADMIN__ . __LIST_U__;
            echo "<a href=\"$location\">Back to page edit user</a>";
            die();
        }
        $result = $db->delete_user($id);
        if (!$result)
            alert_js('مشکلی در حذف کاربر پیش آمد', __LIST_U__, true);
        elseif ($result === 2)
            alert_js('شما نمی توانید این کاربر را حذف کنید زیرا سفارشی برای او ثبت شده', __LIST_U__, true);
        else
            alert_js('کاربر با موفقیت حذف شد', __LIST_U__, true);
    }
}
