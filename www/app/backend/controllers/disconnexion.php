<?php
require_once '../requests/user.php';
require_once '../requests/database.php';

if (isset($_GET['action']) && $_GET['action'] === 'userLeft') {

    $u = new User(Database::getConnection());
    $u->clearSession();
    Database::closeConnection();
    echo json_encode(value: ['status' => 200, 'message' => "deconnexion"]);
}
exit;