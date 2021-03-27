<?php

include "functions//functions.php";
$link = connectBD();
session_start();
//var_dump($_SESSION);
if (!empty($_GET['code'])) {
  // Отправляем код для получения токена (POST-запрос).
  $params = array(
    'client_id'     => '115360450264-0c5bm6ordjpp30h4dalte7aapue6qfq3.apps.googleusercontent.com',
    'client_secret' => 'hgIdq_xtZO1KcmAya_MBIf1P',
    'redirect_uri'  => url(),
    'grant_type'    => 'authorization_code',
    'code'          => $_GET['code']
  );

  $ch = curl_init('https://accounts.google.com/o/oauth2/token');
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_HEADER, false);
  $data = curl_exec($ch);
  curl_close($ch);

  $data = json_decode($data, true);
  if (!empty($data['access_token'])) {
    // Токен получили, получаем данные пользователя.
    $params = array(
      'access_token' => $data['access_token'],
      'id_token'     => $data['id_token'],
      'token_type'   => 'Bearer',
      'expires_in'   => 3599
    );

    $info = file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?' . urldecode(http_build_query($params)));
    $info = json_decode($info, true);

    $login = $info['email'];
    $query = "SELECT `id` FROM `users` WHERE login=?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "s", $login);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    if (empty($id)) {
      $name = $info['name'];
      $query = "INSERT INTO `users` (`login`, `name`) VALUES (?, ?)";
      $stmt = mysqli_prepare($link, $query);
      mysqli_stmt_bind_param($stmt, "ss", $login, $name);
      mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
    }
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "s", $login);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    $_SESSION['picture'] = $info['picture'];
    $_SESSION['auth'] = true;
    $_SESSION['id'] = $id;
    $_SESSION['login'] = $login;
    $_SESSION['name'] = $info['name'];
    $url = url();
    header("Location: $url");
  }
} else {
  if (empty($_SESSION['auth']) or $_SESSION['auth'] == false) {
    if (!empty($_COOKIE['login']) and !empty($_COOKIE['key'])) {
      $login = $_COOKIE['login'];
      $key = $_COOKIE['key'];
      $query = "SELECT `id`, `login`, `name` FROM `users` WHERE login=? AND cookie=?";

      $stmt = mysqli_prepare($link, $query);
      mysqli_stmt_bind_param($stmt, "ss", $login, $key);
      mysqli_stmt_execute($stmt);
      $user['id'] = null;
      $user['name'] = null;
      $user['login'] = null;
      mysqli_stmt_bind_result($stmt, $user['id'], $user['login'], $user['name']);
      mysqli_stmt_fetch($stmt);
      mysqli_stmt_close($stmt);
      if (!empty($user)) {
        $_SESSION['auth'] = true;
        $_SESSION['id'] = $user['id'];
        $_SESSION['login'] = $user['login'];
        $_SESSION['name'] = $user['name'];
      }
    } else {
      $url = url();
      header("Location: $url/auth.php");
    }
  }
}

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Главная страница</title>
  <link rel="stylesheet" href="css/style.css">
  <script> </script>
</head>

<body>
  <div class="header">
    <div class="header-user-info">
      <?php
      if (!empty($_SESSION['picture'])) {
      ?>
        <img src="<?= $_SESSION['picture'] ?>" alt="" class="header-user-info_img">
      <?php
      }
      ?>
      <div class="header-user-info_name">
        <?= $_SESSION['name'] ?>
      </div>

    </div>

    <a class="header-logout_btn btn" href="<?= url() ?>/logout.php">Выход</a>
  </div>
  <div class="main-block">
    <?php
    $userId = $_SESSION['id'];

    $query = "SELECT `id`, `name`, `description`, `last_change` FROM `check_lists` WHERE `user_id`=?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $rowCount = mysqli_stmt_num_rows($stmt);
    $listItem['id'] = null;
    $listItem['name'] = null;
    $listItem['description'] = null;
    $listItem['last_change'] = null;
    mysqli_stmt_bind_result($stmt, $listItem['id'], $listItem['name'], $listItem['description'], $listItem['last_change']);
    ?>
    <div class="main-block-head">
      <div class="main-block-head_header">Список чек-листов</div>
      <div class="main-block-head-items-info">
        <div class="main-block-head_count">Элементов списка: <?= $rowCount ?></div>
        <a class="main-block-head_new-btn btn" href="#openModal">Добавить элемент</a>
      </div>
    </div>
    <div class="main-block-check-lists">
      <?php
      while (mysqli_stmt_fetch($stmt)) {
      ?>
        <div class="main-block-check-list-item">
          <div class="main-block-check-list-info">
            <div class="main-block-check-list-item-info-name"><a href="<?= url() . '/check_list.php?check_list_id=' . $listItem['id'] ?>" class="list-item-info-name_link"><?= $listItem['name'] ?></a></div>
            <div class="main-block-check-list-item-info_description"><?= $listItem['description'] ?></div>
          </div>

          <div class="main-block-check-list-time"><?= date('d.m.Y H:i', strtotime($listItem['last_change'])); ?></div>
        </div>
      <?php
      }
      mysqli_stmt_close($stmt);
      ?>
    </div>
  </div>
  <div id="openModal" class="add-new-checklist">
    <div>
      <div class="add-new-checklist-header">
        <div class="add-new-checklist-header-text">
          Создание нового чек-листа
        </div>
        <a href="#close" title="Закрыть" class="close">&times;</a>
      </div>
      <form action="<?= url() ?>/ajax/add_new_check.php" method="POST">
        <div class="add-new-checklist-name add-block">
          <label class="add-new-checklist-name_label add-form-label" for="name">Название:</label>
          <input class="add-new-checklist-name_input add-form-input" type="text" name="name" id="name" placeholder="Введите название">
        </div>

        <div class="add-new-checklist-description add-block">
          <label class="add-new-checklist-description_label add-form-label" for="description">Описание:</label>
          <textarea class="add-new-checklist-description_input add-form-input" type="text" name="description" id="description"></textarea>
        </div>

        <div class="add-new-checklist-save add-block">
          <button class="add-new-checklist-save_button btn" type="submit">Добавить</button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>