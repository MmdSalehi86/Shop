<?php

if (!isset($_SESSION['id'])) {
    header("location: ../auth/login.php");
    die;
}
