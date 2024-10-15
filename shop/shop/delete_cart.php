<?php
session_start();
require_once "../lib/check_registered.php";
include "../lib/db.php";


if (isset($_GET['p_id'])) {
    $db = new DB();
    $result = $db->delete_cart($_SESSION['id'], $_GET['p_id']);
    if (!$result) {
        alert_js('مشکلی پیش آمد', 'shop_cart.php', true);
    } else {
        alert_js('با موفقیت حذف شد', 'shop_cart.php', true);
    }
}

header(__LOC__ . __SHOP_C__);