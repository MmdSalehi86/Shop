<?php
session_start();

include "../lib/db.php";
include "../lib/file.php";

$duplicate_user = false;
$er_conn_db = false;

if (isset($_SESSION['id'])) {
    if ($_SESSION['access'] != 0)
        header(__LOC__ . __BDIR__ . __D_ADMIN__ . __ADMIN__);
    else
        header(__LOC__ . __BDIR__ . __SHOP__);
    die();

} elseif (isset_post()) {

    $name = $_POST['nam'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    // $pic = $_POST['pic'] ?? null;

    $db = new DB();
    $file = new File();
    //if ($_FILES['pic']['error'] == 0)
        //$pic = $file->get_upload_file($_FILES['pic']['tmp_name'], $_FILES['pic']['name']);
    $result = $db->insert_user($name, $mobile, $email, $address, '', $password, 0);
    if ($result == 2) {   // check duplicate email or phone
        $duplicate_user = true;

    } elseif (!$result) {
        $er_conn_db = true;

    } else {
        $_SESSION = $_POST;
        $_SESSION['id'] = $result['id'];
        $_SESSION['access'] = 0;
        alert_js('شما با موفقیت ثبت نام شدید', __BDIR__ . __SHOP__);
        die();
    }
}
function isset_post(): bool
{
    if (isset($_POST['nam']) and isset($_POST['mobile']) and isset($_POST['email']) and isset($_POST['address'])
        and isset($_POST['password']))
        return true;
    return false;
}
//die;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="../css/font/fonts.css">
    <title>ثبت نام</title>
</head>
<body>

<h1 style="color: red">
    <?php if ($duplicate_user) echo 'شماره موبایل یا ایمیل قیلا ثبت شده است';
    elseif ($er_conn_db) echo 'Error: Connection Database !'; ?>
</h1>
<div class="d1">
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
    <div class="d2">
        <form action="" method="post" enctype="multipart/form-data">
            <h2 class="title">ثبت نام</h2>
            <input class="in1" type="text" name="nam" placeholder="نام" required value="<?php if (isset($name)) echo $name; ?>"/>
            <br/>

            <input class="in2" type="tel" name="mobile" placeholder="شماره تلفن" required
                   value="<?php if (isset($mobile)) echo $mobile ?>"/> <br/>

            <input class="in3" type="email" name="email" placeholder="ایمیل" required
                   value="<?php if (isset($email)) echo $email ?>"/>
            <br/>

            <input class="in4" type="password" name="password" placeholder="رمز عبور" required
                   value="<?php if (isset($password)) echo $password ?>"/><br/>

            <textarea class="in5" name="address" cols="30" rows="5" placeholder="آدرس" required
            ><?php if (isset($address)) echo $address; ?></textarea> <br/>

            <br/>

            <button class="btn" type="submit"><?php if (isset($id)) echo 'ویرایش'; else echo 'ثبت نام'; ?></button>
        </form>
        <br>
        <a type="button" onclick="location.href='login.php'">ورود به حساب</a>
    </div>
</div>
</body>
</html>