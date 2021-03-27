<?php

include "..//functions//functions.php";
$link = connectBD();
session_start();

$userId = $_SESSION['id'];
if (!empty($userId)) {
    $checkName = $_POST['name'];
    $checkDescription = $_POST['description'];
    $query = "INSERT INTO `check_lists`(`name`, `user_id`, `description`) VALUES (?,?,?)";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "sis", $checkName, $userId, $checkDescription);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $url = url();
    header("Location: $url/");
}
