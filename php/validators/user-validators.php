<?php

/**
 * Функция валидации полей формы регистрации
 * @param mysqli $link Ресурс подключения к базе данных
 * @param array $form_data Данные из формы регистрации
 * @return array|null Возвращает массив с кодами ошибок
 */

function validateRegistrationForm(mysqli $link, array $form_data): ?array
{
    $errors = [];
    $required = ['name', 'email', 'password', 'contact',];

    $errors['name'] = validateRegistrationName($form_data['name']);
    $errors['email'] = validateRegistrationEmail($link, $form_data['email']);
    $errors['password'] = validateRegistrationPassword($form_data['password']);
    $errors['contact'] = validateRegistrationContact($form_data['contact']);

    foreach ($required as $val) {
        if (empty($errors[$val])) {
            unset($errors[$val]);
        }
    }
    return array_filter($errors);
}

/**
 * Функция проверки заполнения поля имя
 * @return string|null Возвращает код ошибки при наличии
 * @var string $name Данные из формы
 */

function validateRegistrationName(string $name): ?string
{
    if (empty($name)) {
        $error = 'Введите имя';
    }
    if (strlen($name) > 100) {
        $error = 'Имя не должно превышать 100 символов';
    }
    return $error ?? null;
}

/**
 * Функция проверки заполнения поля email
 * @param string $email Данные из поля формы
 * @return string|null Возвращает код ошибки при наличии
 */

function validateInputEmail(string $email): ?string
{
    if (empty($email)) {
        $error = 'Введите ваш email';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Введите корректный email';
    }
    return $error ?? null;
}

/**
 * Функция проверки введенного email
 * @param string $email Данные из поля email
 * @param mysqli $link Объект подключения к базе данных
 * @return string|null Возвращает код ошибки при наличии
 */

function validateRegistrationEmail(mysqli $link, string $email): ?string
{
    $error = validateInputEmail($email);
    $user = getUserByEmail($link, $email);
    if ($user) {
        $error = 'Пользователь с таким email уже существует';
    }
    return $error ?? null;
}

/**
 * Функция проверки введенного пароля
 * @param string $password Данные из поля формы
 * @return string|null Возвращает код ошибки при наличии
 */

function validateRegistrationPassword(string $password): ?string
{
    if (empty($password)) {
        $error = 'введите пароль';
    }

    if (strlen($password) > 128) {
        $error = 'пароль не должен превышать 128 символов';
    }
    return $error ?? null;
}

/**
 * Функция проверки валидности поля contact
 * @param string $contact Данные из формы регистрации
 * @return string|null Возвращает код ошибки при наличии
 */

function validateRegistrationContact(string $contact): ?string
{
    if (empty($contact)) {
        $error = 'Поле необходимо заполнить';
    }

    if (strlen($contact) > 256) {
        $error = 'Запись не должна превышать 256 символов';
    }
    return $error ?? null;
}

/**
 * Функция проверки валидности формы регистрации
 * @param mysqli $link Ресурс подключения к базе данных
 * @param array $form_data Данные из полей формы
 * @return array|null Возвращает массив с кодами ошибок при наличии
 */

function validateLoginForm(mysqli $link, array $form_data): ?array
{
    $errors = [];
    $required = ['email', 'password'];

    $errors['email'] = validateLoginEmail($link, $form_data['email']);
    $errors['password'] = validateLoginPassword($link, $form_data['email'], $form_data['password']);

    foreach ($required as $val) {
        if (empty($errors[$val])) {
            unset($errors[$val]);
        }
    }
    return $errors ?? null;
}

/**
 * Функция проверки заполнения и уникальности поля email
 * @param string $email Данные из поля email
 * @param mysqli $link Ресурс подключения к базе данных
 * @return string|null Возвращает код ошибки при наличии
 */

function validateLoginEmail(mysqli $link, string $email): ?string
{
    $error = validateInputEmail($email);
    $user = getUserByEmail($link, $email);
    if (!$user) {
        $error = 'Пользователь с таким email не найден';
    }
    return $error ?? null;
}

/**
 * Функция проверки валидности поля password
 * @param mysqli $link Ресурс подключения к базе данных
 * @param string $password Данные из поля password
 * @param string $email Данные из поля email
 * @return string|null
 */

function validateLoginPassword(mysqli $link, string $email, string $password): ?string
{
    $error = validateRegistrationPassword($password);
    $user = getUserByEmail($link, $email);
    if ($user) {
        if (!password_verify($password, $user['password'])) {
            $error = 'Вы ввели неверный пароль';
        }
    }
    return $error ?? null;
}
