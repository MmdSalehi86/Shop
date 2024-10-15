<?php
session_start();

include "../lib/check_admin.php";
include "../lib/db.php";
$db = new DB();
$result = $db->sel_user();
if (!$result) {
    die('ERROR: SELECT QUERY!');
}
$access = $_SESSION['access'];
$id = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لیست کاربران</title>
    <link rel="stylesheet" href="../css/list.css">
    <link rel="stylesheet" href="list.css">
    <link rel="stylesheet" href="../css/font/fonts.css">
    <style>tr{font: 400 13px/1.2 "IRANYekanMedium", Helvetica, Arial, serif;font-style: normal;font-weight: 800;line-height: normal;}</style>
</head>
<body>

<h1>لیست کاربران</h1>
<button class="b1" type="button" onclick="location.href='add_edit_user.php'">ثبت کاربر</button>
<button class="b2" type="button" onclick="location.href='admin.php'">بازگشت</button>
<div>
    <table>
        <tr>
            <th>ID</th>
            <th>نام</th>
            <th>موبایل</th>
            <th>ایمیل</th>
            <th>آدرس</th>
            <th>رمز عبور</th>
            <th>دسترسی</th>
            <th>تصویر نمایه</th>
            <th>ویرایش</th>
            <th>حذف</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr style="<?php if ($_SESSION['id'] == $row['id']) echo 'background-color: #9BFFF9FF;'; ?>">
                <td><?php echo $row['id']?></td>
                <td><?php echo $row['nam'] ?></td>
                <td><?php echo $row['mobile'] ?></td>
                <td><?php echo $row['email'] ?></td>
                <td><?php echo $row['address'] ?></td>
                <td><?php
                    if ($access != $row['access'] and ($access == 2 and $row['access'] == 1)
                        or ($row['id'] == $id) or $row['access'] == 0)
                        echo $row['password']; else echo '&#149 &#149 &#149 &#149 &#149';?></td>
                <td style="background-color:<?php echo$row['access']==2?'#ff3a3a':($row['access']==1?'yellow':'#00ff00')?>">
                    <?php if ($row['access'] == 0)echo'user';
                elseif ($row['access'] == 1)echo'admin';
                else echo 'owner';?></td>
                <td><img src="../resource/user/<?= $row['pic'] ?>" alt="" width="50px" height="50px"></td>
                <td class="edit-delete" onclick="onclick_edit(<?php echo "$row[id], '$row[access]'" ?>)">ویرایش</td>
                <td class="edit-delete" onclick="onclick_delete(<?php echo "$row[id], '$row[nam]', '$row[access]'" ?>)">حذف</td>
            </tr>
        <?php } ?>
    </table>
</div>

<script>
    const user_id = <?php echo $_SESSION['id']; ?>;
    const user_name = "<?php echo $_SESSION['nam']?>";
    const user_access = "<?php echo $_SESSION['access']?>";
    function onclick_delete(id, name, access) {
        if (name === user_name) {
            alert('نمی توانید خودتان را حذف کنید');
        } else {
            if (user_access <= access)
                alert('شما دسترسی برای حذف این کاربر را ندارید');
            else {
                let result = confirm('آیا از حذف کاربر' + ' [ ' + name + ' ] ' + 'اطمینان دارید؟');
                if (result === true)
                    location.href = "delete_user.php?id=" + id + "&del=1";
            }
        }
    }
    function onclick_edit(id, access) {
        if (user_access <= access && user_id !== id)
            alert('شما دسترسی برای ویرایش این کاربر را ندارید');
        else {
            location.href = "add_edit_user.php?id=" + id + "&edit=1";
        }
    }
</script>
</body>
</html>