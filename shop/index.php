<?php
session_start();
include "../lib/db.php";
$db = new DB();

$result = $db->sel_product_with_status(1);

?>

<!DOCTYPE html>
<script src="shop.j"></script>

<?php if (isset($_COOKIE['pr_id']) and isset($_SESSION['id'])) { ?>
    <script>
        add_cart(<?= "$_COOKIE[pr_id], $_SESSION[id]" ?>)
    </script>
    <?php
    setcookie('pr_id', "", time() - 1800, '/', 'localhost');
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="shop.css">
    <link rel="stylesheet" href="../css/font/fonts.css">
    <title>صفحه اصلی فروشگاه</title>
</head>
<body>
    <div class="hed">
        <h1 class="hh">PCکاشان</h1>
        <nav>
            <?php if (isset($_SESSION['id'])){ ?>
                <button class="b1" onclick="location.href='../auth/login.php?logout=1'">خروج</button>
            <?php } else {?>
                <button class="b1" onclick="location.href='../auth/login.php'">ورود | ثبت نام</button>
            <?php } ?>

            <button class="bt_sabad1" onclick="location.href='shop_cart.php'">
                <img class="sabad1" src="basket.svg" alt="sabad2">
            </button>
            <a href="">صفحه اصلی</a>
            <a href="">فروشگاه</a>
            <a href="">درباره ما</a>
        </nav>
    </div>
    <div class="baner">
    </div>
    <div class="d_flex">
        <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="d_product">
            <div class="d2_product">
                <img class="img_product" src="../resource/product/<?= $row['pic']?>" alt="<?= $row['title']?>">
            </div>
            <div>
                <h2><?= $row['title']?></h2>
                <div style="display: flex;flex-wrap: nowrap;">
                    <button class="bt_sabad2"
                            onclick="add_cart(<?= (isset($_SESSION['id'])) ? "$row[id], $_SESSION[id]" : "$row[id], -1"?>)">
                        <img class="sabad2" src="basket.svg" alt="sabad2">
                    </button>
                    <span><?= number_format($row['price'],0, '', '/')?>تومان</span>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
    <br>
    <br>
    <br>
    <footer>
            <div class="foot">
                <h1 class="hf">PCکاشان</h1>
                <h4>دفتر مرکزی - تهران ، خیابان خرمشهر ، بالاتر از بهشتی ، کوچه جاوید ، پلاک ۲۴</h4>
                <h4>۷ روز هفته، ۲۴ ساعته پاسخگوی شما هستیم</h4>
                <h4> کد پستی: ۶۱۹۳۰۰۰۰ | تلفن پشتیبانی: ۶۱۹۳۰۰۰۰ - ۰۲۱</h4>
                <img class="img1" src="Social Buttons.png" alt="">
                <img class="img2" src="logo.png" alt="">
            </div>
        </footer>
</body>
</html>