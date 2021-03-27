<?php

function connectBD()
{
    $config = require(__DIR__ . "//..//config.php");
    $connect = mysqli_connect($config['server'], $config['login'], $config['password'], $config['db'])
        or die("Ошибка " . mysqli_error($connect));
    return $connect;
}

function url()
{
    $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
    $url = explode('?', $url);
    $url = $url[0];
    return $url;
}

function generateSalt()
{
    $salt = '';
    $saltLength = 8;
    for ($i = 0; $i < $saltLength; $i++) {
        $salt .= chr(mt_rand(33, 126));
    }
    return $salt;
}
