<?php
session_start();

include "../lib/check_admin.php";
include "../lib/db.php";
$db = new DB();
$result = $db->sel_product();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لیست محصولات</title>
    <link rel="stylesheet" href="../css/list.css">
    <link rel="stylesheet" href="list.css">
    <link rel="stylesheet" href="../css/font/fonts.css">
    <script src="../js/product_list.js"></script>
    <style>tr{font: 400 13px/1.2 "IRANYekanMedium", Helvetica, Arial, serif;font-style: normal;font-weight: 800;line-height: normal;}</style>
</head>
<body>
<h1>لیست محصولات</h1>
<button class="b1" type="button" onclick="location.href='add_edit_product.php'">ثبت محصول</button>
<button class="b2" type="button" onclick="location.href='admin.php'">بازگشت</button>
<div>
    <table>
        <tr>
            <th>ID</th>
            <th>عنوان</th>
            <th>توضیحات</th>
            <th>قیمت</th>
            <th>تعداد</th>
            <th>وضعیت</th>
            <th>تصویر محصول</th>
            <th>ویرایش</th>
            <th>حذف</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['title'] ?></td>
                <td style="width: 20%"><?php echo $row['description'] ?></td>
                <td><?= number_format($row['price'], 0, '', '/') ?></td>
                <td><?= $row['tedad'] ?></td>
                <td style="background-color: <?php echo $row['status'] == 1 ? '#00ff00' : '#ff3a3a' ?> ;">
                    <?= $row['status'] == 1 ? 'فعال' : 'غیرفعال' ?>
                </td>
                <td><img src="../resource/product/<?= $row['pic'] ?>" alt="" width="50px" height="50px"></td>
                <td class="edit-delete" onclick="onclick_edit(<?= $row['id']; ?>)">ویرایش</td>
                <td class="edit-delete" onclick="onclick_delete(<?= "$row[id],'$row[title]'"; ?>)">حذف</td>
            </tr>
        <?php } ?>
    </table>
</div>
<script>
    function onclick_edit(id) {
        window.location.href = 'add_edit_product.php?id=' + id + "&edit=1";
    }

    function onclick_delete(id, name) {
        let result = confirm('آیا از حذف کالای' + ' [' + name + '] ' + ' مطمئن هستید؟');
        if (result === true)
            window.location.href = 'delete_product.php?id=' + id + "&del=1";
    }

</script>
</body>
</html>