<?php
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

$routes = [
    "admin_dashboard" => [
        "role" => ["admin"],
        "page" => "admin_dashboard.php"
    ],
    "user_profile" => [
        "role" => ['admin', 'user'],
        "page" => "user_profile.php"
    ],
    "home" => [
        "role" => ["everybody"],
        "page" => "home.php"
    ],
    "stat" => [
        "role" => ["everybody"],
        "page" => "stat.php"
    ],
    "register" => [
        "role" => ["guest"],
        "page" => "register.php"
    ],
    "login" => [
        "role" => ["guest"],
        "page" => "login.php"
    ],
    "error" => [
        "role" => ["everybody"],
        "page" => "error.php"
    ],
];

$pageRequest = $_GET['page'] ?? null;
if (!isset($_SESSION['user']['role']) || !$_SESSION['user']['role'] ) {
    $_SESSION['user']['role'] = 'guest';
}

$currentRole = $_SESSION['user']['role'];
if (!$pageRequest || !isset($routes[$pageRequest])) {

    // Utiliser correctement la clé 'page' dans $routes['error']
    $file = ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . basename($routes['error']['page']);
   
    ob_start();
    require $file;
    http_response_code(200); 
    $content = ob_get_clean();
    echo $content;
    exit;
}
$route = $routes[$pageRequest];

if (
    !in_array("everybody", $route['role']) &&
    !in_array($currentRole, $route['role'])
) {
    showError(403, "Vous n'avez pas les droits pour accéder à cette page.");
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