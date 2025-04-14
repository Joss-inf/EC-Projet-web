<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
function validateEmail($email): mixed
{
    return filter_var(value: $email, filter: FILTER_VALIDATE_EMAIL);
}

function getPasswordError($password): array
{
    $errors = [];
    if (strlen(string: $password) < 8) {
        $errors[] = "Le mot de passe doit être 8 caractère de long minimum.";
    }
    if (!preg_match(pattern: '/[0-9]/', subject: $password)) {
        $errors[] = "Le mot de passe doit contenir au moin un chiffre.";
    }
    if (!preg_match(pattern: '/[A-Z]/', subject: $password)) {
        $errors[] = "Le mot de passe  doit contenir au moin une majuscule";
    }
    if (!preg_match(pattern: '/[\W_]/', subject: $password)) {
        $errors[] = "Le mot de passe  doit contenir au moin un caractère spécial";
    }
    return $errors;
}
function validatePassword($password): bool
{

    if (strlen(string: $password) < 8) {
        return false;
    }
    if (!preg_match(pattern: '/[0-9]/', subject: $password)) {
        return false;
    }
    if (!preg_match(pattern: '/[A-Z]/', subject: $password)) {
        return false;
    }
    if (!preg_match(pattern: '/[\W_]/', subject: $password)) {
        return false;
    }
    return true;
}