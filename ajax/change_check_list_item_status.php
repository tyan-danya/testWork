<?php

include "..//functions//functions.php";
$link = connectBD();
session_start();

$checkListItemId = $_POST['id'];
$checkListItemStatus = $_POST['status'];
$query = "UPDATE `check_list_items` SET `check` = '$checkListItemStatus' WHERE `check_list_items`.`id` = $checkListItemId;";
$result = mysqli_query($link, $query);
