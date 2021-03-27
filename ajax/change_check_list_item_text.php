<?php

include "..//functions//functions.php";
$link = connectBD();
session_start();

$checkListItemId = $_POST['id'];
$checkListItemText = $_POST['text'];
$query = "UPDATE `check_list_items` SET `description` = '$checkListItemText' WHERE `check_list_items`.`id` = $checkListItemId;";
$result = mysqli_query($link, $query);
