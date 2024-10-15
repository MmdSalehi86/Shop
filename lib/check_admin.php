<?php

if (isset($_SESSION['id'])) {
    if ($_SESSION['access'] == 0) {
        echo '<h1 style="font-weight: bold">Access Denied</h1>';
        echo '<h2 style="color:red; font-weight: bold">ERROR CODE: 403</h2>';
        echo '<a href="../">Back to the main page</a><br>';
        die();
    }
} else {
    header('location: ../');
}