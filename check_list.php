<?php
include "functions//functions.php";
$link = connectBD();
session_start();


if (empty($_SESSION['auth']) or $_SESSION['auth'] == false) {
    $url = url();
    header("Location: $url/auth.php");
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
        <div class="header-buttons">
            <a class="header-home_btn btn" href="<?= url() ?>">Главная</a>
            <a class="header-logout_btn btn" href="<?= url() ?>/logout.php">Выход</a>
        </div>

    </div>
    <div class="main-block">
        <?php
        $checkListId = $_GET['check_list_id'];
        $_SESSION['check_list_id'] = $checkListId;

        $query = "SELECT `name` FROM `check_lists` WHERE `id`=?";

        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "i", $checkListId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $listName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        $query = "SELECT `id`, `name` ,`description`, `check` FROM `check_list_items` WHERE `check_list_id`=?";

        $stmt = mysqli_prepare($link, $query);
        mysqli_stmt_bind_param($stmt, "i", $checkListId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $rowCount = mysqli_stmt_num_rows($stmt);
        $listItem['id'] = null;
        $listItem['name'] = null;
        $listItem['description'] = null;
        $listItem['check'] = null;
        mysqli_stmt_bind_result($stmt, $listItem['id'], $listItem['name'], $listItem['description'], $listItem['check']);
        ?>
        <div class="main-block-head">
            <div class="main-block-head_header">Чек-лист - <?= $listName ?></div>
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
                    <div class="main-block-check-list-item-header">
                        <div class="main-block-check-list-item-name">
                            <input class="main-block-check-list-item-name_checkbox" id="main-checkbox_<?= $listItem['id'] ?>" type="checkbox" name="" id="" data-id=<?= $listItem['id'] ?> <?= $listItem['check'] == 1 ? 'checked' : '' ?>>
                            <label for="main-checkbox_<?= $listItem['id'] ?>"><?= $listItem['name'] ?></label>
                        </div>
                        <div class="main-block-check-list-additional_button" data-id=<?= $listItem['id'] ?>>+</div>
                    </div>
                </div>
                <div class="main-block-check-list-item-additional" data-id=<?= $listItem['id'] ?>>
                    <div class="main-block-check-list-item-additional-text">

                        <div class="list-item-additional-text_text" data-id=<?= $listItem['id'] ?>><?= $listItem['description'] ?></div>
                        <img class="list-item-additional-save" src="img/ok.png" data-id=<?= $listItem['id'] ?> alt="">
                        <img class="list-item-additional-change" src="img/pen.png" data-id=<?= $listItem['id'] ?> alt="">

                    </div>


                    <div class="list-item-additional-list">
                        <?php
                        $listItemId = $listItem['id'];
                        $query = "SELECT `id`, `name`, `check` FROM `checkboxes` WHERE `check_list_item_id`=?";
                        $_stmt = mysqli_prepare($link, $query);
                        mysqli_stmt_bind_param($_stmt, "i", $listItemId);
                        mysqli_stmt_execute($_stmt);
                        $chexbox['id'] = null;
                        $chexbox['name'] = null;
                        $chexbox['description'] = null;
                        $chexbox['check'] = null;
                        mysqli_stmt_bind_result($_stmt, $chexbox['id'], $chexbox['name'], $chexbox['check']);
                        while (mysqli_stmt_fetch($_stmt)) {
                        ?>
                            <div class="list-item-additional-list-checkbox">
                                <input class="list-item-additional-list_item" type="checkbox" id="checkbox_<?= $chexbox['id'] ?>" data-id=<?= $chexbox['id'] ?> <?= $chexbox['check'] == 1 ? 'checked' : '' ?>>
                                <label for="checkbox_<?= $chexbox['id'] ?>"><?= $chexbox['name'] ?></label>
                            </div>

                        <?php
                        }
                        mysqli_stmt_close($_stmt);
                        ?>
                        <a class="list-item-additional-list-checkbox_add-btn btn" data-id=<?= $listItem['id'] ?> href="#openModal1">Добавить элемент</a>
                    </div>
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
                    Создание нового элемента чек-листа
                </div>
                <a href="#close" title="Закрыть" class="close">&times;</a>
            </div>
            <form action="<?= url() ?>/ajax/add_new_check_item.php" method="POST">
                <div class=" add-new-checklist-name add-block">
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

    <div id="openModal1" class="add-new-checklist">
        <div>
            <div class="add-new-checklist-header">
                <div class="add-new-checklist-header-text">
                    Создание нового подпункта элемента чек-листа
                </div>
                <a href="#close" title="Закрыть" class="close">&times;</a>
            </div>
            <div class=" add-new-checklist-name add-block">
                <label class="add-new-checklist-name_label add-form-label" for="name1">Название:</label>
                <input class="add-new-checklist-name_input add-form-input" type="text" name="name" id="name1" placeholder="Введите название">
            </div>

            <div class="add-new-checklist-save add-block">
                <button class="add-new-checklist-save_button btn" type="submit">Добавить</button>
            </div>
        </div>
    </div>
    <script src="script/check_list.js"></script>
</body>

</html>