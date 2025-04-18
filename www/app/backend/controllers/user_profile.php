<?php
require_once '../requests/user.php';
require_once '../requests/database.php';
require_once './validators.php';
session_start();
// Vérifie si l'action est 'getUser'
if (isset($_GET['action']) && $_GET['action'] === 'getUser') {
    if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] !== 'guest') {

        echo json_encode([
            'status' => 200,
            'message' => [
                'username' => $_SESSION['user']['name'],
                'email' => $_SESSION['user']['email']
            ]
        ]);
    } else {
        echo json_encode([
            'status' => 400,
            'message' => 'Utilisateur non connecté.'
        ]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User(Database::getConnection());
    // Lire le contenu JSON envoyé dans le body de la requête
    $data = json_decode(file_get_contents("php://input"), true);

    // Vérifier si toutes les données sont présentes
    if (
        isset($data['action']) && $data['action'] === 'update_profile' &&
        isset($data['email']) && isset($data['pseudo']) &&
        isset($data['current_password']) && isset($data['new_password'])
    ) {


        $email = $data['email'];
        $pseudo = $data['pseudo'];
        $currentPassword = $data['current_password'];
        $newPassword = $data['new_password'];
        $confirm_password = $data['confirm_password'];
        $userId = $_SESSION['user']['id'];
    
        if (!validateName($pseudo)) {
            http_response_code(400);
            echo json_encode(['status' => 400, 'message' => 'Format de nom  incorrect']);
            exit;
        }

        if (!validateEmail($email)) {
            http_response_code(400);
            echo json_encode(['status' => 400, 'message' => 'Format d\' email incorrect']);
            exit;
        }

        if ($newPassword !== '' || $confirm_password !== '') {
            if (!validatePassword($newPassword)) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => "mot  de passe  non conforme (8 de long, une maj ,un chiffre, un caractere special)"]);
                exit;
            }

            if ($newPassword !== $confirm_password) {
                http_response_code(400);
                echo json_encode(['status' => 400, 'message' => 'Les mots de passe ne sont pas identiques']);
                exit;
            }
        }
    
       
        $booleanPassword = $user -> checkpassword($currentPassword,$userId);
        if ($booleanPassword ){
            // Si le mot de passe actuel est correct, mettre à jour les informations

            // Mettre à jour l'email et le pseudo
            $updateProfileSuccess = $user->updateUserProfile($userId, $email, $pseudo); // Mettez en place cette fonction pour mettre à jour l'email et le pseudo dans la base de données

            // Mettre à jour le mot de passe si un nouveau mot de passe est fourni
            if (!empty($newPassword)) {
                // Hacher le nouveau mot de passe
                $hashedNewPassword = password_hash(password: $newPassword, algo: PASSWORD_DEFAULT, options: ['cost' => 12]);

                // Mettre à jour le mot de passe dans la base de données
                $updatePasswordSuccess = $user->updateUserPassword($userId, $hashedNewPassword); // Mettez en place cette fonction pour mettre à jour le mot de passe
            }

            // Vérifier si la mise à jour a réussi
            if ($updateProfileSuccess) {
                echo json_encode([
                    'status' => 200,
                    'message' => 'Profil et mot de passe mis à jour avec succès.'
                ]);
     
            } else {
                echo json_encode( [
                    'status' => 400,
                    'message' => 'Erreur lors de la mise à jour du profil.'
                ]);
            }


            exit;
        } else {
            // Si le mot de passe actuel est incorrect
            echo json_encode([
                'status' => 400,
                'message' => 'Le mot de passe actuel est incorrect.'
            ]);
            exit;
        }
    } else {
        // Si certaines données sont manquantes
        echo json_encode([
            'status' => 400,
            'message' => 'Données manquantes ou invalides.'
        ]);
        exit;
    }
} 
?>