-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3300
-- Время создания: Мар 28 2021 г., 01:50
-- Версия сервера: 8.0.19
-- Версия PHP: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `tasks`
--

-- --------------------------------------------------------

--
-- Структура таблицы `checkboxes`
--

CREATE TABLE `checkboxes` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `check_list_item_id` int NOT NULL,
  `check` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `checkboxes`
--

INSERT INTO `checkboxes` (`id`, `name`, `check_list_item_id`, `check`) VALUES
(1, 'Стоит понимать', 1, 1),
(3, 'Анализа стандартных подходов', 1, 1),
(18, ' анализ нестандартных элементов', 1, 1),
(22, 'bananamama', 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `check_lists`
--

CREATE TABLE `check_lists` (
  `id` int NOT NULL,
  `name` varchar(45) NOT NULL,
  `user_id` int NOT NULL,
  `description` varchar(255) NOT NULL,
  `last_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `check_lists`
--

INSERT INTO `check_lists` (`id`, `name`, `user_id`, `description`, `last_change`) VALUES
(1, 'чекитем 1', 4, 'прекрасное подробное и понятное описание этого итема', '2021-03-20 20:15:57'),
(2, 'чекитем2', 4, 'не менее прекрасное и очень нужное описание ', '2021-03-20 20:17:56'),
(3, 'чекитем1', 1, '', '2021-03-20 20:15:57');

-- --------------------------------------------------------

--
-- Структура таблицы `check_list_items`
--

CREATE TABLE `check_list_items` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `check_list_id` int NOT NULL,
  `description` varchar(500) NOT NULL,
  `check` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `check_list_items`
--

INSERT INTO `check_list_items` (`id`, `name`, `check_list_id`, `description`, `check`) VALUES
(1, 'Дальнейшее развитие различных форм деятельности', 1, 'Таким образом реализация намеченных плановых заданий позволяет оценить значение новых предложений. Повседневная практика показываетвапвап, что реализация намеченных плановых заданий в значительной степени обуславливает создание модели развития.', 1),
(2, 'sdfsdf', 5, 'sdfsdf', 0),
(3, 'Перспективное планирование играет важную роль', 1, 'Таким образом реализация намеченных плановых заданий позволяет оценить значение новых предложений. Повседневная практика показывает, что реализация намеченных плановых заданий в значительной степени обуславливает создание модели развития.', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `login` varchar(45) NOT NULL,
  `password` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `name` varchar(45) NOT NULL,
  `salt` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `cookie` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `name`, `salt`, `cookie`) VALUES
(1, 'gunter334', '2cf2e8751b0c1cfdc03af3fe4876b3db', 'Даниил', 'Zpm;|k\"2', '^?B[G1WC'),
(2, 'bober12rus', '42c11b89387c45e26345c1208e3231ce', 'Александр', '],\'\"s]oJ', ''),
(4, 'daniil.tyan.2001@gmail.com', '', 'Даниил Тян', '', ''),
(5, 'daniil.tyan.1980@gmail.com', NULL, 'daniil tyan', NULL, NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `checkboxes`
--
ALTER TABLE `checkboxes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `check_lists`
--
ALTER TABLE `check_lists`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `check_list_items`
--
ALTER TABLE `check_list_items`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `checkboxes`
--
ALTER TABLE `checkboxes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `check_lists`
--
ALTER TABLE `check_lists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `check_list_items`
--
ALTER TABLE `check_list_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
