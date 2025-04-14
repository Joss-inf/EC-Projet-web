<?php
session_start();
require_once '../requests/user.php';
require_once '../requests/database.php';
require_once 'validators.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(response_code: 501);
    exit;
}
$data = json_decode(json: file_get_contents(filename: 'php://input'), associative: true);

$name =$data['name'] ?? '';
$email = $data['email'] ?? '';
$confirm_email = $data['confirm_email'] ?? '';
$password = $data['password'] ?? '';
$confirm_password = $data['confirm_password'] ?? '';

if (!validateEmail($email)) {
    http_response_code(response_code: 200);
    echo json_encode(value: ['status' => 400, 'message' =>'Format d\' email incorrect']);
    exit;
}

if ($email != $confirm_email) {
    http_response_code(response_code: 400);
    echo json_encode(value: ['status' => 400, 'message' => 'Les emails ne correspondent pas']);
    exit;
}

if (!validatePassword($password)) {
    http_response_code(response_code: 400);
    echo json_encode(value: ['status' => 400, 'message' => getPasswordError($password)]);
    exit;
}

if ($password != $confirm_password) {
    http_response_code(response_code: 400);
    echo json_encode(value: ['status' => 400, 'message' => 'les mots de passes ne sont pas identiques']);
    exit;
}

$u = new User(Database::getConnection());
$res = $u->register( $email, $name,$password);
if ($res[0] != 200) {
    http_response_code(response_code: $res[0]);
    echo json_encode(value: ['status' => $res[0], 'message' => $res[1]]);
    exit;
}
$res = null;
$res = $u->login($email, $password);
if ($res[0] == 200) {
    $user = $res[1];
    $_SESSION['user_id'] = $user['id'] ?? '';
    $_SESSION['email'] = $user['email'] ?? '';
    $_SESSION['role'] = $user['role'] ?? '';
    $_SESSION['pharmacy_id'] = $user['pharmacy_id'] ?? '';
    echo json_encode(value: ['status' => $resp[0], 'message' => 'login succes']);
    exit;
}
echo json_encode(value: ['status' => $res[0], 'message' => $res[1]]);
exit;

