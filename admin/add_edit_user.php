<?php
session_start();

include '../lib/check_admin.php';
include "../lib/db.php";
include "../lib/file.php";

$duplicate_user = false;
$er_conn_db = false;
$access_user = $_SESSION['access'];

$name = '';
$mobile = '';
$email = '';
$address = '';
$password = '';
$access = '';

$set_post = false;
isset_post();

$db = new DB();
$file = new File();
//  edit and delete user
if (isset($_GET['id']) and !empty($_GET['id'])) {
    $id = $_GET['id'];   // get id for edit user
    if (isset($_GET['edit'])) {
        if ($set_post) {
            $_POST['id'] = $id;   // send user id for edit
            if (!isset($_POST['access']))
                $_POST['access'] = $_SESSION['access'];
            if ($_FILES['pic']['error'] == 0)
                $_POST['pic'] = $file->get_upload_file($_FILES['pic']['tmp_name'], $_FILES['pic']['name']);

            $result = $db->edit_user($_POST);
            if (!$result)
                alert_js('مشکلی در ویرایش کاربر به وجود آمد', __LIST_U__, true);
            elseif ($result === 2) {
                $duplicate_user = true;
            } else
                alert_js('کاربر با موفقیت ویرایش شد', __LIST_U__, true);

        } else {
            $data_user = $db->sel_user($id);
            $name = $data_user['nam'];
            $mobile = $data_user['mobile'];
            $email = $data_user['email'];
            $address = $data_user['address'];
            $password = $data_user['password'];
            $access = $data_user['access'];
            // picture
        }
    }
} elseif ($set_post) {
    if ($_FILES['pic']['error'] == 0)
        $pic = $file->get_upload_file($_FILES['pic']['tmp_name'], $_FILES['pic']['name']);
    $result = $db->insert_user($_POST['nam'], $_POST['mobile'], $_POST['email'], $_POST['address'], $pic ?? '', $_POST['password'], $_POST['access']);
    if (!$result)
        $er_conn_db = true;
    elseif ($result === 2)
        $duplicate_user = true;
    else
        alert_js('کاربر با موفقیت ثبت نام شد', __ADMIN__, true);
}

function isset_post()
{
    if (isset($_POST['nam']) and isset($_POST['mobile']) and isset($_POST['email']) and isset($_POST['address'])
        and isset($_POST['password'])) {
        $GLOBALS['set_post'] = true;
        global $name, $mobile, $email, $address, $password, $access;
        $name = $_POST['nam'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $password = $_POST['password'];
        if (isset($_POST['access']))
            $access = $_POST['access'];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="uedit.css">
    <link rel="stylesheet" href="../css/font/fonts.css">
    <title>ثبت نام</title>
</head>
<body>

<h1>
    <?php if ($duplicate_user) echo 'شماره موبایل یا ایمیل قبلا ثبت شده است';
    elseif ($er_conn_db) echo 'Error: Connection Database !'; ?>
</h1>
<div class="d1">
<div class="d2">
<form action="" method="post" enctype="multipart/form-data">
    <h1>ثبت کاربر</h1>
    <input class="in1" type="text" name="nam" placeholder="نام" required value="<?php if (isset($name)) echo $name; ?>"/>
    <br/>

    <input class="in2" type="tel" name="mobile" placeholder="شماره تلفن" required
           value="<?php if (isset($mobile)) echo $mobile ?>"/>
    <br/>

    <input class="in2" type="email" name="email" placeholder="ایمیل" required value="<?php if (isset($email)) echo $email ?>"/>
    <br/>

    <input class="in2" type="password" name="password" placeholder="رمز عبور" required
           value="<?php if (isset($password)) echo $password ?>"/>
    <br/>

    <?php if (!isset($id) or ($id != $_SESSION['id'])) { ?>
        <select class="in2" name="access" required>
            <option value="">انتخاب سطح دسترسی</option>
            <option value="0"
                <?php if (isset($access) and $access == 0) echo 'selected'; ?>>کاربر
            </option>
            <?php if ($access_user == 2) { ?>
                <option
                        value="1" <?php if (isset($access) and $access == 1) echo 'selected'; ?>>ادمین
                </option>
            <?php } ?>
        </select>
        <br/>
    <?php } ?>

    <textarea class="in5" name="address" cols="30" rows="5" placeholder="آدرس"
              required><?php if (isset($address)) echo $address; ?></textarea> <br/>

              <div class="d3" >
        <svg onclick="document.getElementById('file').click()" xmlns="http://www.w3.org/2000/svg" width="190" height="150" viewBox="0 0 190 190" fill="none" class="svg1">
            <path d="M95 166.25C134.35 166.25 166.25 134.35 166.25 95C166.25 55.6497 134.35 23.75 95 23.75C55.6497 23.75 23.75 55.6497 23.75 95C23.75 134.35 55.6497 166.25 95 166.25Z"
                  stroke="black" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M136.297 153.047C126.15 141.715 111.407 134.583 94.9987 134.583C78.5901 134.583 63.8473 141.715 53.7003 153.047"
                  stroke="black" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M95 110.833C108.117 110.833 118.75 100.2 118.75 87.0833C118.75 73.9666 108.117 63.3333 95 63.3333C81.8832 63.3333 71.25 73.9666 71.25 87.0833C71.25 100.2 81.8832 110.833 95 110.833Z"
                  stroke="black" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <input style="display: none" id="file" type="file" name="pic" accept=".jpg,.png,.jpeg"/>
    </div>
    <button class="btn" type="submit"><?php if (isset($id)) echo 'ویرایش';
        else echo 'ثبت نام'; ?></button>
    <?php if (isset($id)) echo
    '<button class="btn1" type="button" onclick="location.href=\'list_user.php\'">بازگشت</button>';
    else echo
    '<button class="btn1" type="button" onclick="location.href=\'admin.php\'">بازگشت</button>'; ?>
    <button class="btn2" type="reset">ریست</button>
    </div>
    </div>
</form>
</body>
</html>