<?php

include "..//functions//functions.php";
$link = connectBD();
session_start();

$checkListId = $_SESSION['check_list_id'];
if (!empty($checkListId)) {
    $checkName = $_POST['name'];
    $checkDescription = $_POST['description'];
    $query = "INSERT INTO `check_list_items`(`name`, `check_list_id`, `description`, `check`) VALUES (?,?,?, 0)";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "sis", $checkName, $checkListId, $checkDescription);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $url = url();
    header("Location: $url/check_list.php?check_list_id=$checkListId");
}
