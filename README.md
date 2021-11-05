`composer install`

copy `example-config.yaml` to `config.yaml` and edit

php -f spam.php

works via smtp and web server not need
=========================================================
result mail: https://github.com/BorisTimYa/SpamEngine/blob/495f7bfc4f14387a14406e265d90292d34d9b0d5/reportMail.png
spam mail: https://github.com/BorisTimYa/SpamEngine/blob/495f7bfc4f14387a14406e265d90292d34d9b0d5/spamMail.png
=========================================================

ЗАДАЧА для создания email рассылки (~~для ее решения нужен веб-сервер~~)


1) Имеется массив с Emails пользователей.
   В себя включает: Имя, Email, Возраст, Дата регистрации, Отключение рассылки

$users = [
['name' => 'Владимир', 'email' => 'vlad@saytum.ru', 'age' => 14, 'date_registration' => 1395338433, 'spam_disable' => 0],
['name' => 'Яна', 'email' => 'yura@saytum.ru', 'age' => 23, 'date_registration' => 1394128833, 'spam_disable' => 0],
['name' => 'Василий', 'email' => 'vlad@saytum.ru', 'age' => 22, 'date_registration' => 1092110433, 'spam-disabled' => 1],
['name' => 'Дима', 'email' => 'dima@saytum.ru', 'age' => 11, 'date_registration' => 1191647013, 'spam_disable' => 0],
['name' => 'Мария', 'email' => 'yura@saytum.ru', 'age' => 30, 'date_registration' => 1223269413, 'spam_disable' => 0],
['name' => 'Василий', 'email' => 'vlad@saytum.ru', 'age' => 22, 'date_registration' => 1209877593, 'spam_disable' => 0],
['name' => 'Павел', 'e-mail' => 'dima@saytum.ru', 'age' => 11, 'date_registration' => 1052046393, 'spam_disable' => 1],
['name' => 'Юлия', 'email' => 'yura@saytum.ru', 'age' => 18, 'date_registration' => 1367665593, 'spam_disable' => 0],
['name' => 'Василий', 'email' => 'vlad@yandex941kffrq.ru', 'age' => 22, 'date_registration' => 1209877593, 'spam_disable' => 0],
['name' => 'Павел', 'email' => 'dima@yandex941kffrq.ru', 'age' => 11, 'date_registration' => 1052046393, 'spam_disable' => 1],
['name' => 'Юлия', 'email' => 'yura@yandex941kffrq.ru', 'age' => 18, 'date_registration' => 1367665593, 'spam_disable' => 0],
['name' => 'Яна', 'email' => 'yura3xs31aytum.ru31', 'age' => 23, 'date_registration' => 1394128833, 'spam_disable' => 0],
['name' => 'Василий', 'email' => 'vladqr3saytum.ru', 'age' => 22, 'date-registration' => 1092110433, 'spam_disable' => 1],
['name' => 'Дима', 'email' => 'dimaqr41saytum.ru', 'age' => 11, 'date_registration' => 1191647013, 'spam_disable' => 0],
];

2) Нужно создать функцию автоматической рассылки Email писем (с аргументами: содержимое письма, почта пользователя и др. если нужно), при этом:

0. Сделать проверку Email на корректность, в случае некорректого Email отправлять об этом сообщение, сообщив Имя пользователя (в отчет (см. пункт 3.3))

1. Формат письма должен быть в HTML
2. В теле письма должен быть заголовок h1 с приветствием и именем

3) Создать рассылку писем по пользователям из массива $users задействовав нашу функцию.

-1. У рассылки должна быть возможность ограничить рассылку по почтовому домену, например: saytum.ru
0. Рассылка не должна срабатывать позже 22 часов и раньше 10 утра.
1. Пользователь должен быть совершен	нолетним
2. Рассылка должна происходить по дате регистрации пользователя, тот кто зарегистрирован раньше должен получить письмо первым.
3. После завершения рассылки отправить отчет о завершении рассылки и количестве отправленных писем и об ошибках (если имеются) на почту pavlova@saytum.ru
