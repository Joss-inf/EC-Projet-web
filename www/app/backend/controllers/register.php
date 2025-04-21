<?php
session_start();
require_once '../requests/user.php';
require_once '../requests/database.php';
require_once 'validators.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(501);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$name = $data['name'] ?? '';
$email = $data['email'] ?? '';
$confirm_email = $data['confirm_email'] ?? '';
$password = $data['password'] ?? '';
$confirm_password = $data['confirm_password'] ?? '';

// Validation
if (!validateName($name)) {
    http_response_code(400);
    echo json_encode(['status' => 400, 'message' => 'Format de nom  incorrect']);
    exit;
}

if (!validateEmail($email)) {
    http_response_code(400);
    echo json_encode(['status' => 400, 'message' => 'Format d\' email incorrect']);
    exit;
}

if ($email !== $confirm_email) {
    http_response_code(400);
    echo json_encode(['status' => 400, 'message' => 'Les emails ne correspondent pas']);
    exit;
}

if (!validatePassword($password)) {
    http_response_code(400);
    echo json_encode(['status' => 400, 'message' => getPasswordError($password)]);
    exit;
}

if ($password !== $confirm_password) {
    http_response_code(400);
    echo json_encode(['status' => 400, 'message' => 'Les mots de passe ne sont pas identiques']);
    exit;
}

// Création utilisateur
$u = new User(Database::getConnection());
$res = $u->register($email, $name, $password);
if ($res[0] != 200) {
    http_response_code($res[0]);
    echo json_encode(['status' => $res[0], 'message' => $res[1]]);
    exit;
}

// Connexion automatique après inscription
$res = $u->login($email, $password);
if ($res[0] == 200) {
    $user = $res[1];
    $_SESSION['user'] = [
        'id' => $user['id'] ?? '',
        'email' => $user['email'] ?? '',
        'role' => $user['user_state'] ?? '',
        'name' => $user['name'] ?? '',
    ];

    echo json_encode(['status' => $res[0], 'message' => 'login succes']);
    exit;
}

// Échec du login
echo json_encode(['status' => $res[0], 'message' => $res[1]]);
exit;


