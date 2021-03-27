<?php

include "..//functions//functions.php";
$link = connectBD();
session_start();

$checkListItemItemsId = $_POST['id'];
$checkListItemItemsStatus = $_POST['status'];
$query = "UPDATE `checkboxes` SET `check` = '$checkListItemItemsStatus' WHERE `checkboxes`.`id` = $checkListItemItemsId;";
echo $query;
$result = mysqli_query($link, $query);
