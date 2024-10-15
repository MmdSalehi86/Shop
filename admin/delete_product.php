<?php
session_start();
include "../lib/check_admin.php";
include "../lib/db.php";

if (isset($_GET['id']) and !empty($_GET['id'])) {
    $id_product = $_GET['id'];
    $db = new DB();
    if (isset($_GET['del'])) {
        $result = $db->delete_product($id_product);
        if (!$result)
            alert_js('مشکلی در حذف کالا به وجود آمد', __LIST_P__, true);

        elseif ($result === 2)
            alert_js('هیچ کالایی پیدا نشد', __LIST_P__, true);

        elseif ($result === 3)
            alert_js('سفارشی برای این کالا وجود دارد. پس نمی توانید آن را حذف کنید', __LIST_P__, true);

        else
            alert_js('کالا  با موفقیت حذف شد', __LIST_P__, true);
    }
}