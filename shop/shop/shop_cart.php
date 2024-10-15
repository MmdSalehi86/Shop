<?php
session_start();
include "../lib/check_registered.php";
include "../lib/db.php";

$db = new DB();

if (isset($_GET['buy'])){
    $buy = $_GET['buy'];
    
    if ($buy === 'true'){
        $db->buy_product($_SESSION['id'], $_GET['p_id']);
       alert_js('خرید با موفقیت انجام شد', 'shop_cart.php', true); 
    }
}
elseif (isset($_GET['p_id'])) {
    $product_id = $_GET['p_id'];
    $user_id = $_SESSION['id'];

    $result = $db->insert_to_cart($product_id, $user_id, 1);
    if ($result === 2)
        alert_js('محصول مورد نظر پیدا نشد', './', true);
    elseif ($result === 3)
        alert_js('نمی توانید بیشتر از تعداد محصول انتخاب کنید', './', true);
    elseif (!$result)
        alert_js('مشکلی در ارتباط با پایگاه داده پیش آمد', './', true);
    else
        header(__LOC__ . './');
    die;
}

$result = $db->sel_cart($_SESSION['id']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cart.css">
    <link rel="stylesheet" href="../css/font/fonts.css">
    <title>سبد خرید</title>
    <link rel="stylesheet" href="../css/list.css">
</head>
<body>

<h1 style="text-align: right;">سبد خرید</h1>

<table>
    <tr>
        <th>محصول</th>
        <th>تاریخ</th>
        <th>مبلغ کل</th>
        <th>تعداد</th>
        <th>حذف</th>
        <th>خرید</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result))  if ($row['status'] == 0) {?>
        <tr>
            <td><?= $row['title'] ?></td>
            <td dir="ltr"><?= $row['date_order'] ?></td>
            <td id="price<?= $row['product_id']?>" dir="ltr">
                <?= number_format($row['price'], 0, '', '/') ?> T
            </td>

            <td>
                <img width="16" height="16" src="plus.png"
                     alt="plus--v1" onclick="update_cart_p(<?php echo "$row[product_id], $_SESSION[id]"?>)"/>
                <span id="tedad<?= $row['product_id'] ?>"><?= $row['tedad'] ?></span>

                <img width="16" height="16" src="minus.png"
                     onclick="update_cart_m(<?php echo "$row[product_id], $_SESSION[id]"?>)" alt="minus-sign"/>
            </td>
            <td style="cursor: pointer" onclick="location.href='delete_cart.php?p_id=<?= $row['product_id']?>'">حذف</td>
            <td style="cursor: pointer" onclick="location.href='shop_cart.php?buy=true&p_id=<?= $row['product_id']?>'">خرید</td>
        </tr>
    <?php } ?>
</table>
<button class="b2" type="button" onclick="location.href='./'">بازگشت</button>

</body>
</html>
<script src="cart.js"></script>