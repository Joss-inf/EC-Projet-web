<?php
ini_set('display_errors', 1);  // Active l'affichage des erreurs
error_reporting(E_ALL);        // Affiche toutes les erreurs

session_start(); // Si tu utilises des rôles ou des sessions utilisateur

// Rôle actuel de l'utilisateur (par défaut : invité)
$currentRole = $_SESSION['role'] ?? 'guest';

// Liste blanche des routes valides
$routes = [
    "admin_dashboard" => [
        "role" => "admin",
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

// 🔐 Ne fais confiance à aucune entrée
$pageRequest = $_GET['page'] ?? null;

// 🔒 Vérifie que la page demandée est bien dans la liste blanche
if (!$pageRequest || !isset($routes[$pageRequest])) {
    http_response_code(400);
    echo "<p>Requête invalide</p>";
    exit;
}

$route = $routes[$pageRequest];

// 🔒 Vérifie que l'utilisateur a le bon rôle
if ($currentRole !== $route['role'] && $route['role'] !== 'guest') {
    http_response_code(403);
    echo "<p>Accès refusé</p>";
    exit;
}

// 🔒 Sécurise le chemin du fichier à inclure
$file =  ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR .  "views" . DIRECTORY_SEPARATOR . basename($route['page']);

if (!file_exists($file)) {
    http_response_code(500);
    echo "<p>Erreur interne : fichier manquant</p>";
    exit;
}

// ✅ Si tout est OK, on charge proprement la page
ob_start();
require $file;
http_response_code(200);
$content = ob_get_clean();
echo $content;
exit;