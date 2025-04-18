<?php
session_start();

if (isset($_GET['action']) && $_GET['action'] === 'getSession') {
    if(!isset($_SESSION['user'])){
        $user = 'guest' ;
    }else{
        $user = $_SESSION['user']['role'] ?? 'guest' ;
    }
    echo json_encode(['status' => 200, 'message' => $user ]);
}
exit;