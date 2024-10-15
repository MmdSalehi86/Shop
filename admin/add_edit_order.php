<?php
session_start();
include "../lib/check_admin.php";
include "../lib/db.php";

$set_post = false;
isset_post();

$db = new DB();

$result_user = $db->sel_user();
$result_product = $db->sel_product();

if (isset($_GET['user_id']) and isset($_GET['product_id']) and isset($_GET['date'])) {

    $user_id = $_GET['user_id'];
    $product_id = $_GET['product_id'];
    $date_order = $_GET['date'];
    $result_order = $db->sel_order($user_id, $product_id, $date_order);

    if ($set_post) {
        $result = $db->update_order($user_id, $product_id, $date_order, $_POST['sel_user'], $_POST['sel_product'], $_POST['date'], $_POST['tedad'], $_POST['status']);
        if (!$result)
            alert_js('مشکلی در ویرایش کالا به وحود آمد', __LIST_O__, true);
        elseif ($result === 2)
            alert_js('کالا یا کاربر مورد نظر برای ویرایش سفارش پیدا نشد');
        elseif ($result === 3)
            alert_js('نمی توانید این محصول را سفارش دهید، زیرا تعداد محصول از تعداد سفارش شما کمتر است');
        elseif ($result === 4)
            alert_js('وضعیت این محصول غیرفعال است یا موجودی آن تمام شده، نمی توانید آن را سفارش دهید');
        else
            alert_js('سفارش با موفقیت ویرایش شد', __LIST_O__, true);
    }

} elseif ($set_post) {
    $result = $db->insert_order($_POST['date'], $_POST['sel_product'], $_POST['sel_user'], $_POST['tedad'], $_POST['status']);
    if (!$result)
        alert_js('مشکلی در افزودن سفارش به وجود آمد');
    elseif ($result === 2)
        alert_js('کاربر مورد نظر پیدا نشد');
    elseif ($result === 3)
        alert_js('کالای مورد نظر پیدا نشد');
    elseif ($result === 4)
        alert_js('وضعیت کالای مورد نظر غیرفعال است');
    elseif ($result === 5)
        alert_js('تعداد سفارش شما بیشتر از تعداد محصول است');
    else
        alert_js('سفارش با موفقیت اضافه شد', __ADMIN__, true);
}

function isset_post()
{
    if (isset($_POST['sel_user']) and isset($_POST['sel_product']) and isset($_POST['date']) and isset($_POST['tedad'])
        and isset($_POST['status']))
        $GLOBALS['set_post'] = true;
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php if (isset($user_id)) echo 'ویرایش سفارش';
        else echo 'ثبت سفارش'?></title>
</head>
<body dir="rtl">
<div>
    <form action="" method="post">

        <label for="sel_user">انتخاب کاربر</label>
        <select name="sel_user" id="sel_user" required>
            <option value="">انتخاب کاربر</option>
            <?php while ($row = $result_user->fetch_assoc()) { ?>
                <option value="<?php echo $row['id'] ?>"
                    <?php if ((isset($user_id) and $row['id'] == $user_id) or ($set_post and $_POST['sel_user'] == $row['id']))
                        echo 'selected'; ?>><?= "$row[id] : $row[nam]"?></option>
            <?php } ?>
        </select>
        <br/>

        <label for="sel_product">انتخاب کالا</label>
        <select name="sel_product" id="sel_product" required>
            <option value="">انتخاب محصول</option>
            <?php while ($row = $result_product->fetch_assoc()) if ($row['status'] == 1) { ?>
                <option value="<?= $row['id'] ?>"
                    <?php if ((isset($product_id) and $row['id'] == $product_id) or ($set_post and $_POST['sel_product'] == $row['id']))
                        echo 'selected'; ?>><?php echo "$row[id] : $row[title]" ?></option>
            <?php } ?>
        </select>
        <br/>

        <label for="date">تاریخ سفارش</label>
        <input type="text" name="date" id="date" required dir="ltr"
               value="<?= isset($result_order) ? $result_order['date_order'] : ($set_post ? $_POST['date'] : get_full_time()) ?>">
        <br/>

        <label for="tedad">تعداد</label>
        <input type="number" min="1" name="tedad" id="tedad" required
               value="<?= isset($result_order) ? $result_order['tedad'] : ($set_post ? $_POST['tedad'] : 1) ?>">
        <br/>

        <label for="status"></label>
        <select name="status" id="status" required>
            <option value="">وضعبت سفارش</option>
            <option value="0"
                <?php if ((isset($result_order) and $result_order['status'] == 0)  or ($set_post and $_POST['status'] == 0))
                    echo 'selected' ?>>پرداخت نشده</option>
            <option value="1"
                <?php if ((isset($result_order) and $result_order['status'] == 1) or ($set_post and $_POST['status'] == 1))
                    echo 'selected' ?>>پرداخت شده</option>
        </select>
        <br/>

        <button type="submit"><?= isset($user_id) ? 'ویرایش سفارش' : 'ثبت سفارش' ?></button>
        <button type="reset">ریست</button>
    </form>
</div>
<br>
<button type="button" onclick="location.href='list_order.php'">بازگشت</button>

</body>
</html>
