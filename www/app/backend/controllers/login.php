<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../requests/user.php';
    require_once '../requests/database.php';
     $data = json_decode(file_get_contents('php://input'), true);
    // Récupération des données
    $password = $data['password'] ?? '';
    $email = $data['email'] ?? '';
    if (empty($password)) {
        echo json_encode(value: ['status' => 400, 'message' => 'Mot de passe manquant.']);
        exit;
    }
    $u = new User(Database::getConnection());
    $res = $u->login($email, $password);

    if ($res[0] == 200) {
        $user = $res[2];
        $_SESSION['user'] = [
            'id' => $user['id'] ?? '',
            'email' => $user['email'] ?? '',
            'role' => $user['user_state'] ?? '',
            'name' => $user['username'] ?? '',
        ];
        $string =  $user['user_state'] ?? '';
        echo json_encode(value: ['status' => $res[0], 'message' => $string]);
        exit;
    } else {
        echo json_encode(value: ['status' => $res[0], 'message' => $res[1]]);
    }
    exit;
}