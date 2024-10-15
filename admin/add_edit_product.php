<?php
session_start();

include "../lib/check_admin.php";
include "../lib/db.php";
include "../lib/file.php";
$db = new DB();
$file = new File();

$set_post = false;
isset_post();

if (isset($_GET['id']) and !empty($_GET['id'])) {
    $id_product = $_GET['id'];
    if (isset($_GET['edit'])) {
        if ($set_post) {
            $_POST['id'] = $_GET['id'];
            if ($_FILES['pic']['error'] == 0)
                $_POST['pic'] = $file->get_upload_file($_FILES['pic']['tmp_name'], $_FILES['pic']['name'], true);
            $result = $db->insert_update_product($_POST);
            if (!$result)
                alert_js('مشکلی در ویرایش کالا به وجود آمد', __LIST_P__, true);
            elseif ($result === 2)
                alert_js('سفارشی پرداخت شده برای این محصول وجود دارد! نمی توانید وضعیت را ویرایش کنید', __LIST_P__, true);
            else
                alert_js('کالا با موفقیت ویرایش شد', __LIST_P__, true);

        } else {
            $result = $db->sel_product($id_product)->fetch_assoc();
            if (!$result) {
                alert_js('هیچ کالایی پیدا نشد', __LIST_P__);
                die();
            }
        }
    }
} elseif ($set_post) {
    if ($_FILES['pic']['error'] == 0)
        $_POST['pic'] = $file->get_upload_file($_FILES['pic']['tmp_name'], $_FILES['pic']['name'], true);
    $db->insert_update_product($_POST);

    alert_js('کالا با موفقیت ثبت شد', __ADMIN__);
    die();
}

function isset_post()  // check post method
{
    if (isset($_POST['name']) and isset($_POST['price']) and isset($_POST['tedad']) and isset($_POST['description'])
        and isset($_POST['status']))
        $GLOBALS["set_post"] = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pedit.css">
    <link rel="stylesheet" href="../css/font/fonts.css">
    <title>
        <?php if (isset($id_product))
            echo 'ویرایش محصول';
        else
            echo 'ثبت محصول' ?>
    </title>
</head>
<body>
<div class="d2" style="border: 0px solid black;width: 680px;height: 400px;border-radius: 30px;position: absolute; left: 27%; top: 19%;">

    <form action="" method="post" enctype="multipart/form-data" style="position: absolute;left: 10px;" dir="rtl">
        <h1>ثبت محصول</h1>

        <input class="in1" type="text" name="name" id="name" placeholder="نام محصول" required
               value="<?php echo $result['title'] ?? '' ?>"/> <br/>

        <input class="in2" type="text" name="price" id="price" placeholder="قیمت" required
               value="<?php echo $result['price'] ?? '' ?>"><br/>

        <input class="in2" type="number" name="tedad" id="tedad" placeholder="تعداد" min="0" required
               value="<?php echo $result['tedad'] ?? '' ?>"/> <br/>

        <textarea class="in5" name="description" id="description" placeholder="توضیحات" cols="21"
                  rows="3"><?php echo $result['description'] ?? '' ?></textarea> <br/>

        <select class="in2" name="status" id="status" onchange="change_status(this.value)">
            <option value="1" <?php if (isset($result) and $result['status'] == 1) echo 'selected' ?>>فعال</option>
            <option value="0" <?php if (isset($result) and $result['status'] == 0) echo 'selected' ?>>غیرفعال</option>
        </select><br/>

        <!-- <input type="file"  name="pic" accept=".jpg,.png,.jpeg"/> -->

        <button class="btn" type="submit">ثبت</button>

        <input src="<?php echo $result['pic'] ?? '' ?>" alt="" type="file" poster="bag.gif" name="pic" accept=".jpg,.png,.jpeg" style="background-image: url('');position: absolute;left: 350px;top: 40px;width: 300px;height: 280px;border: 0px;border-radius: 40px;background: #f1efef;box-shadow: 0px 2px 30px #b3b3b3;border-radius: 48px; "/>

        <button class="btn1" type="reset">ریست</button>
        <br/>

        <?= isset($_GET['id']) ? '<button type="button" onclick="location.href=\'list_product.php\'">بازگشت</button>' :
            '<button class="btn2" type="button" onclick="location.href=\'admin.php\'">بازگشت</button>'; ?>
    </form>
</div>
<script>
    const input_tedad = document.getElementById('tedad');

    function change_status(value) {
        if (value === "1") {
            if (input_tedad.value == '0')
                input_tedad.value = 1;
            input_tedad.min = 1;
        } else if (value === "0")
            input_tedad.min = 0;
    }
</script>
</body>
</html>