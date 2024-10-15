<?php
session_start();

include "../lib/check_admin.php";
include "../lib/db.php";

$db = new DB();
$result = $db->sel_order();
$id = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لیست سفارش ها</title>
    <link rel="stylesheet" href="../css/list.css">
    <link rel="stylesheet" href="list.css">
    <link rel="stylesheet" href="../css/font/fonts.css">
    <style>tr{font: 400 13px/1.2 "IRANYekanMedium", Helvetica, Arial, serif;font-style: normal;font-weight: 800;line-height: normal;}</style>
</head>
<body>
<h1>لیست سفارش</h1>
<button class="b1" type="button" onclick="location.href='add_edit_order.php'">ثبت سفارش</button>
<button class="b2" type="button" onclick="location.href='admin.php'">بازگشت</button>
<div>
    <table>
        <tr>
            <th>ID</th>
            <th colspan="2">سفارش دهنده</th>
            <th>محصول</th>
            <th>تاریخ</th>
            <th>مبلغ</th>
            <th>مبلغ کل</th>
            <th>تعداد</th>
            <th>وضعیت</th>
            <th>ویرایش</th>
            <th>حذف</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo ++$id ?></td>
                <td><?php echo $row['nam']; ?></td>
                <td><?php echo $row['mobile']; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['date_order'] ?></td>
                <td><?php echo $row['price'] / $row['tedad'] ?></td>
                <td><?php echo $row['price'] ?></td>
                <td><?php echo $row['tedad'] ?></td>
                <td style="background-color: <?php if ($row['status'] == 1) echo '#00ff00'; else echo '#ff3a3a'?>;">
                    <?php if ($row['status'] == 1) echo 'پرداخت شده'; else echo 'پرداخت نشده'; ?>
                </td>

                <td class="edit-delete"
                    onclick="onclick_edit(<?php echo "'$row[user_id]' , '$row[product_id]' , '$row[date_order]', $row[status]"; ?>)">ویرایش</td>
                <td class="edit-delete"
                    onclick="onclick_delete(<?php echo "'$row[user_id]' , '$row[product_id]' , '$row[date_order]', $row[status]"; ?>)">حذف</td>
            </tr>
        <?php } ?>
    </table>
</div>

<script>

    function onclick_edit(user_id, product_id, date_order, status) {
        if (status === 1)
            alert('شما نمی توانید یک سفارش پرداخت شده را ویرایش کنید');
        else
            location.href = "add_edit_order.php?user_id=" + user_id + "&product_id=" + product_id + "&date=" + date_order + "&edit=1";
    }

    function onclick_delete(user_id, product_id, date_order, status) {
        if (status === 1){
            alert('شما نمی توانید یک سفارش پرداخت شده را حذف کنید');
            return;
        }
        let result = confirm('آیا می خواهید این سفارش را حذف کنید؟');
        if (result === true)
            location.href = "delete_order.php?user_id=" + user_id + "&product_id=" + product_id + "&date=" + date_order + "&del=1";
    }
</script>
</body>
</html>