<?php


session_start();
require_once '../requests/user.php';
require_once '../requests/database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$data = $data = json_decode(file_get_contents('php://input'), true);
// Récupération des données
$password = $data['password'] ?? '';
$email = $data['email'] ?? '';
if (empty($password)) {
    echo json_encode(value: ['status' => 400,'message'=> 'Mot de passe manquant.']);
    exit;
}
$u = new User(Database::getConnection());
$res = $u -> login($email,$password);

if($res[0] == 200){
    $user = $res[1];
    $_SESSION['user_id'] = $user['id'] ?? '';
    $_SESSION['email'] = $user ['email']?? '';
    $_SESSION['role'] = $user ['role']?? '';
    $_SESSION['name'] = $user['name'] ?? '';

    echo json_encode(value: ['status' => $res[0],'message'=> $res[1]]);
}else{
    echo json_encode(value: ['status' => $res[0], 'message'=> $res[1]]);
}
exit;
}