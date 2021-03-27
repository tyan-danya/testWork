<?php

include "..//functions//functions.php";
$link = connectBD();
session_start();

$checkListItemId = $_POST['id'];
$checkName = $_POST['name'];
$query = "INSERT INTO `checkboxes`(`name`, `check_list_item_id`, `check`) VALUES (?,?,0)";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "si", $checkName, $checkListItemId);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

$query = "select max(id) as 'id' from `checkboxes`";
$result = mysqli_query($link, $query);
$id = mysqli_fetch_assoc($result)['id'];
echo $id;
