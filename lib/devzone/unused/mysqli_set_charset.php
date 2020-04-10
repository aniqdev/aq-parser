<?php
$link = mysqli_connect('localhost', 'root', '', 'gig_parser');

/* проверка соединения */
if (mysqli_connect_errno()) {
    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
    exit();
}

printf("Изначальная кодировка: %s\n", $link->character_set_name());

/* изменение набора символов на utf8 */
if (!mysqli_set_charset($link, "utf8")) {
    printf("Ошибка при загрузке набора символов utf8: %s\n", mysqli_error($link));
    exit();
} else {
    printf("Текущий набор символов: %s\n", mysqli_character_set_name($link));
}

mysqli_close($link);
?>