<?php
session_start();
include "../lib/check_admin.php";
include "../lib/db.php";

$db = new DB();

if (isset($_GET['user_id']) and isset($_GET['product_id']) and isset($_GET['date'])) {

    $user_id = $_GET['user_id'];
    $product_id = $_GET['product_id'];
    $date_order = $_GET['date'];
    $result_order = $db->sel_order($user_id, $product_id, $date_order);

    if (isset($_GET['del'])) {
        $result = $db->delete_order($date_order, $user_id, $product_id);
        if (!$result)
            alert_js('مشکلی در حذف سفارش پیش آمد', __LIST_O__);
        elseif ($result === 2)
            alert_js('به دلیل اینکه وضعیت این سفارش پرداخت شده است نمی توانید آن را حذف کنید', __LIST_O__);
        else
            alert_js('سفارش با موفقیت حذف شد', __LIST_O__);
        die();

    }
}