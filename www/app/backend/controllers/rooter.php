<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
function showError($code, $message): never
{
    http_response_code(response_code: $code);
    $file = ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "error.php";
    if (file_exists(filename: $file)) {
        // Variables utilisées dans la page error.php
        $code = htmlspecialchars(string: $code);
        $message = htmlspecialchars(string: $message);
        include $file;
    } else {
        echo "<h1>Erreur $code</h1><p>$message</p>";
    }
    exit;
}
$currentRole = $_SESSION['role'] ?? 'guest';
$routes = [
    "admin_dashboard" => [
        "role" => "guest",
        "page" => "admin_dashboard.php"
    ],
    "user_profile" => [
        "role" => "user",
        "page" => "user_profile.php"
    ],
    "home" => [
        "role" => "guest",
        "page" => "home.php"
    ],
    "stat" => [
        "role" => "guest",
        "page" => "stat.php"
    ],
    "register" => [
        "role" => "guest",
        "page" => "register.php"
    ],
    "login" => [
        "role" => "guest",
        "page" => "login.php"
    ],
];
$pageRequest = $_GET['page'] ?? null;
if (!$pageRequest || !isset($routes[$pageRequest])) {
    showError(code: 404, message: "Page demandée introuvable.");
}
$route = $routes[$pageRequest];
if ($currentRole !== $route['role'] && $route['role'] !== 'guest') {
    showError(code: 403, message: "Vous n'avez pas les droits pour accéder à cette page.");
}
$file = ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . basename(path: $route['page']);
if (!file_exists(filename: $file)) {
    showError(code: 500, message: "Le fichier associé à cette page est manquant.");
}
ob_start();
require $file;
http_response_code(response_code: 200);
$content = ob_get_clean();
echo $content;
exit;