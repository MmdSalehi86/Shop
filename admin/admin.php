<?php
session_start();

include "../lib/check_admin.php";

include "../lib/db.php";
$db = new DB();

$result = $db->sel_statistics();
$db->close();

?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="../css/font/fonts.css">
    <title>صفحه مدیریت سایت</title>
</head>
<body>
    <header>
        <h1>پنل مدیریت</h1>
        <button class="button11" type="button" onclick="location.href='list_order.php'">لیست سفارش</button>
        <!-- <button type="button" onclick="location.href='add_edit_order.php'">افزودن سفارش</button> -->
        <button class="button10" type="button" onclick="location.href='list_product.php'">لیست محصولات</button>
        <!-- <button type="button" onclick="location.href='add_edit_product.php'">اضافه کردن محصول</button> -->
        <button class="button1" type="button" onclick="location.href='list_user.php'">لیست کاربران</button>
        <!-- <button type="button" onclick="location.href='add_edit_user.php'">ثبت کاربر</button> -->
        <button class="button2" type="button" onclick="location.href='../auth/login.php?logout'">خروج</button>
    </header>

    <div class="d1">
        <div class="d2">
        <svg style="position: absolute;right: 10px; top: 11px;width: 30px;height: 30px;" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
  <path d="M33.3333 6.66667H6.66667C5.74619 6.66667 5 7.41286 5 8.33333V31.6667C5 32.5871 5.74619 33.3333 6.66667 33.3333H33.3333C34.2538 33.3333 35 32.5871 35 31.6667V8.33333C35 7.41286 34.2538 6.66667 33.3333 6.66667Z" stroke="url(#paint0_linear_30_17)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
  <path d="M25 13.3333C25 16.0948 22.7614 18.3333 20 18.3333C17.2386 18.3333 15 16.0948 15 13.3333" stroke="url(#paint1_linear_30_17)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
  <defs>
    <linearGradient id="paint0_linear_30_17" x1="5" y1="6.66667" x2="46.7916" y2="13.6175" gradientUnits="userSpaceOnUse">
      <stop stop-color="#D2DBFC"/>
      <stop offset="0.412495" stop-color="#B2B8DB"/>
      <stop offset="1" stop-color="#151A93"/>
    </linearGradient>
    <linearGradient id="paint1_linear_30_17" x1="15" y1="13.3333" x2="28.1649" y2="17.226" gradientUnits="userSpaceOnUse">
      <stop stop-color="#D2DBFC"/>
      <stop offset="0.412495" stop-color="#B2B8DB"/>
      <stop offset="1" stop-color="#151A93"/>
    </linearGradient>
  </defs>
</svg>
            <h2 style="position: relative;right: 45px;background: linear-gradient(98deg, #D2DBFC 0%, #B2B8DB 51.45%);
background-clip: text;
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;">آمار محصولات</h2>
            <span>محصولات فعال:</span>
            <span><?php echo $result['e_product']['tedad'];?></span>
            <br/>
            <span>محصولات غیرفعال:</span>
            <span><?php echo $result['d_product']['tedad'];?></span>
            <br/>
            <span>کل محصولات:</span>
            <span><?php echo (int)$result['d_product']['tedad'] + (int)$result['e_product']['tedad'];?></span>
        </div>

        <div class="d3">
        <svg style="position: absolute;right: 10px; top: 11px;width: 30px;height: 30px;" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
  <path d="M6.66666 35C6.66666 30.3976 12.6362 26.6667 20 26.6667C27.3638 26.6667 33.3333 30.3976 33.3333 35" stroke="url(#paint0_linear_28_128)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
  <path d="M20 21.6667C24.6024 21.6667 28.3333 17.9357 28.3333 13.3333C28.3333 8.73096 24.6024 5 20 5C15.3976 5 11.6667 8.73096 11.6667 13.3333C11.6667 17.9357 15.3976 21.6667 20 21.6667Z" stroke="url(#paint1_linear_28_128)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
  <defs>
    <linearGradient id="paint0_linear_28_128" x1="6.66666" y1="26.6667" x2="37.8607" y2="41.4243" gradientUnits="userSpaceOnUse">
      <stop stop-color="#FCD2D2"/>
      <stop offset="0.185" stop-color="#DBB2B2"/>
      <stop offset="1" stop-color="#931715"/>
    </linearGradient>
    <linearGradient id="paint1_linear_28_128" x1="11.6667" y1="5" x2="35.0161" y2="8.45202" gradientUnits="userSpaceOnUse">
      <stop stop-color="#FCD2D2"/>
      <stop offset="0.185" stop-color="#DBB2B2"/>
      <stop offset="1" stop-color="#931715"/>
    </linearGradient>
  </defs>
</svg>
            <h2 style="position: relative;right: 45px;background: linear-gradient(98deg, #FCD2D2 0%, #DBB2B2 51.45%);
background-clip: text;
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;">آمار کاربران</h2>
            <span>تعداد کاربران:</span>
            <span><?php echo $result['user']['tedad']; ?></span>
            <br/>
            <span>تعداد مدیران:</span>
            <span><?php echo $result['admin']['tedad']; ?></span>
            <br/>
            <span>تعداد مدیران کل:</span>
            <span><?php echo $result['owner']['tedad']; ?></span>
            <br/>
            <span>تعداد کل کاربران:</span>
            <span><?php echo $result['user']['tedad'] + $result['admin']['tedad'] + $result['owner']['tedad']; ?></span>
        </div>

        <div class="d4">
        <svg style="position: absolute;right: 10px; top: 11px;width: 30px;height: 30px;" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
  <path d="M28.3333 33.3333C28.3333 32.4128 29.0795 31.6667 30 31.6667C30.9205 31.6667 31.6667 32.4128 31.6667 33.3333C31.6667 34.2538 30.9205 35 30 35C29.0795 35 28.3333 34.2538 28.3333 33.3333Z" stroke="url(#paint0_linear_30_16)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
  <path d="M11.6667 33.3333C11.6667 32.4128 12.4128 31.6667 13.3333 31.6667C14.2538 31.6667 15 32.4128 15 33.3333C15 34.2538 14.2538 35 13.3333 35C12.4128 35 11.6667 34.2538 11.6667 33.3333Z" stroke="url(#paint1_linear_30_16)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
  <path d="M5 5H6.27953C7.0833 5 7.77257 5.57367 7.9185 6.36409L11.6667 26.6667" stroke="url(#paint2_linear_30_16)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
  <path d="M9.16666 10H32.9049C33.9767 10 34.7699 10.9971 34.5289 12.0414L31.452 25.3748C31.2775 26.131 30.6041 26.6667 29.828 26.6667H11.6667" stroke="url(#paint3_linear_30_16)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
  <defs>
    <linearGradient id="paint0_linear_30_16" x1="28.3333" y1="31.6667" x2="33.0032" y2="32.3571" gradientUnits="userSpaceOnUse">
      <stop stop-color="#D2FCD6"/>
      <stop offset="0.412495" stop-color="#B2DBB3"/>
      <stop offset="1" stop-color="#159322"/>
    </linearGradient>
    <linearGradient id="paint1_linear_30_16" x1="11.6667" y1="31.6667" x2="16.3366" y2="32.3571" gradientUnits="userSpaceOnUse">
      <stop stop-color="#D2FCD6"/>
      <stop offset="0.412495" stop-color="#B2DBB3"/>
      <stop offset="1" stop-color="#159322"/>
    </linearGradient>
    <linearGradient id="paint2_linear_30_16" x1="5" y1="5" x2="14.5242" y2="5.43325" gradientUnits="userSpaceOnUse">
      <stop stop-color="#D2FCD6"/>
      <stop offset="0.412495" stop-color="#B2DBB3"/>
      <stop offset="1" stop-color="#159322"/>
    </linearGradient>
    <linearGradient id="paint3_linear_30_16" x1="9.16666" y1="10" x2="43.7792" y2="17.8003" gradientUnits="userSpaceOnUse">
      <stop stop-color="#D2FCD6"/>
      <stop offset="0.412495" stop-color="#B2DBB3"/>
      <stop offset="1" stop-color="#159322"/>
    </linearGradient>
  </defs>
</svg>
            <h2 style="position: relative;right: 45px;background: linear-gradient(98deg, #D2FCD6 0%, #B2DBB3 51.45%);
background-clip: text;
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;">آمار سفارش</h2>
            <span>تعداد سفارشات:</span>
            <span><?php echo $result['order']['tedad']; ?></span>
            <br/>
            <span>مبلغ کل سفارشات:</span>
            <span><?php echo $result['order']['total']; ?></span>
        </div>

        <div class="d5">
        <svg style="position: absolute;right: 10px; top: 11px;width: 30px;height: 30px;" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
  <path d="M8.33331 35C8.33331 28.5567 13.5567 23.3333 20 23.3333C26.4433 23.3333 31.6666 28.5567 31.6666 35" stroke="url(#paint0_linear_30_18)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
  <path d="M20 18.3333C23.6819 18.3333 26.6666 15.3486 26.6666 11.6667C26.6666 7.98477 23.6819 5 20 5C16.3181 5 13.3333 7.98477 13.3333 11.6667C13.3333 15.3486 16.3181 18.3333 20 18.3333Z" stroke="url(#paint1_linear_30_18)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
  <defs>
    <linearGradient id="paint0_linear_30_18" x1="8.33331" y1="23.3333" x2="39.0514" y2="32.4162" gradientUnits="userSpaceOnUse">
      <stop stop-color="#FCF8D2"/>
      <stop offset="0.412495" stop-color="#DBDAB2"/>
      <stop offset="1" stop-color="#909315"/>
    </linearGradient>
    <linearGradient id="paint1_linear_30_18" x1="13.3333" y1="5" x2="32.0129" y2="7.76162" gradientUnits="userSpaceOnUse">
      <stop stop-color="#FCF8D2"/>
      <stop offset="0.412495" stop-color="#DBDAB2"/>
      <stop offset="1" stop-color="#909315"/>
    </linearGradient>
  </defs>
</svg>
            <h2 style="position: relative;right: 45px;background: linear-gradient(98deg, #FCF8D2 0%, #DBDAB2 51.45%);
background-clip: text;
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;">فعال ترین کاربر</h2>
            <span>نام:</span>
            <span><?php echo $result['best_user']['nam'] ?? ''; ?></span>
            <br/>
            <span>موبایل:</span>
            <span><?php echo $result['best_user']['mobile'] ?? ''; ?></span>
            <br>
            <span>تعداد سفارش:</span>
            <span><?php echo $result['best_user']['tedad'] ?? '' ?></span>
        </div>
    </div>
    <img src="867.png" alt="">
</body>
</html>