<?php
session_start();

if (isset($_GET['logout'])) {
    session_unset();
}

$not_found_user = false;

if (isset($_SESSION['id'])) {
    if ($_SESSION['access'] == 0) {
        header('location: ../shop/');
    } else {
        header('location: ../admin/admin.php');
    }
    die();

} elseif (isset($_POST['mobile_email']) and isset($_POST['password'])) {
    include "../lib/db.php";
    $db = new DB();
    $mobile_email = $_POST['mobile_email'];
    $password = $_POST['password'];
    $result = $db->sel_user_with_mobile_or_email($mobile_email, $mobile_email, $password);
    if ($result == 2) {
        $not_found_user = true;

    } else {
        $_SESSION = $result;
        if ($result['access'] == 0)
            header('location: ../shop/');
        else
            header('location: ../admin/admin.php');
        die();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به سایت</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="../css/font/fonts.css">

</head>
<body>
<h1 style="color: red"><?php if ($not_found_user) echo 'اطلاعات وارد شده پیدا نشد'; ?></h1>

<div class="d1">
    <div class="d3">
        <svg xmlns="http://www.w3.org/2000/svg" width="190" height="150" viewBox="0 0 190 190" fill="none" class="svg1">
            <path d="M95 166.25C134.35 166.25 166.25 134.35 166.25 95C166.25 55.6497 134.35 23.75 95 23.75C55.6497 23.75 23.75 55.6497 23.75 95C23.75 134.35 55.6497 166.25 95 166.25Z" stroke="black" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M136.297 153.047C126.15 141.715 111.407 134.583 94.9987 134.583C78.5901 134.583 63.8473 141.715 53.7003 153.047" stroke="black" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M95 110.833C108.117 110.833 118.75 100.2 118.75 87.0833C118.75 73.9666 108.117 63.3333 95 63.3333C81.8832 63.3333 71.25 73.9666 71.25 87.0833C71.25 100.2 81.8832 110.833 95 110.833Z" stroke="black" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
    <div class="d2">
    <form action="" method="post">
        <h2 class="title">ورود</h2>
        <input class="in1" type="text" name="mobile_email" placeholder="شماره موبایل یا ایمیل" required
               value="<?php if (isset($mobile_email)) echo $mobile_email; ?>"><br>
        <input class="in2" type="password" name="password" placeholder="رمز ورود" required
               value="<?php if (isset($password)) echo $password; ?>"><br>
        <button class="btn" type="submit">ورود</button>
    </form>
        <br>
    <a onclick="location.href='register.php'">!اگر حسابی ندارید ایجاد کنید</a>
    </div>
</div>
</body>
</html>