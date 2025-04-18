<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class User
{
    private $db;
    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function register(string $email, string $name, string $password): array
    {
        try {
            $stmt = $this->db->prepare("SELECT 1 FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            if ($stmt->rowCount() > 0) {
                return [400, 'Email existe déjà.'];
            }
            $stmt = $this->db->prepare("SELECT 1 FROM users WHERE username= :username");
            $stmt->execute(['username' => $name]);
            if ($stmt->rowCount() > 0) {
                return [400, 'Nom déjà pris.'];
            }
            // Hacher le mot de passe
            $hached_password = password_hash(password: $password, algo: PASSWORD_DEFAULT, options: ['cost' => 12]);
            // Insérer le nouvel utilisateur
            $stmt = $this->db->prepare("INSERT INTO users (password_hash, email,user_state,username) VALUES (:password, :email,:user_state,:username)");
            // Exécution de la requête avec les données associées
            if ($stmt->execute(['password' => $hached_password, 'email' => $email, 'user_state' => 'user', 'username' => $name])) {
                return [200, 'Inscription réussie.'];
            } else {
                return [400, "Erreur lors de l'inscription."];
            }
        } catch (PDOException $e) {
            // Si une erreur se produit, on capture l'exception et on renvoie l'erreur
            return [500, "Erreur de base de données : " . $e->getMessage()];
        }
    }
    // Connexion de l'utilisateur
    public function login($email, $password): array
    {
        try {
            // Préparer la requête pour récupérer l'utilisateur par email
            $stmt = $this->db->prepare("SELECT password_hash  FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);

            if ($stmt->rowCount() != 1) {
                return [401, 'Email incorrect'];
            }
            $p = $stmt->fetch(PDO::FETCH_ASSOC);
            // Vérifier si le mot de passe est correct

            if (password_verify(password: $password, hash: $p['password_hash'])) {
                // Démarrer une session et stocker les informations de l'utilisateur
                $stmt = $this->db->prepare("SELECT id, username, email, user_state FROM users WHERE email = :email");
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                return [200, "Connexion réussie ! Redirection à la page d'accueil en cours . . .", $user];
            } else {
                return [401, 'Mot de passe incorrect.'];
            }
        } catch (PDOException $e) {
            return [500, "Erreur de base de données : " . $e->getMessage()];
        }
    }
    public function checkpassword($password,$userid): bool
    {
        try {
            $stmt = $this->db->prepare("SELECT password_hash FROM users WHERE id= :id ");
            $stmt->execute(['id' => $userid]);
            $p = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if (password_verify(password: $password, hash: $p['password_hash'])) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            throw new Exception("Erreur de base de données : " . $e->getMessage());
        }
    }
    function getUserById($userId): mixed
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->execute(['id' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur de base de données : " . $e->getMessage());
        }
    }
    function updateUserProfile($userId, $email, $pseudo): mixed
    {
        try {
            $stmt = $this->db->prepare('UPDATE users SET email = :email, username = :pseudo WHERE id = :id');
            return $stmt->execute(['email' => $email, 'pseudo' => $pseudo, 'id' => $userId]);
        } catch (PDOException $e) {
            throw new Exception("Erreur de base de données : " . $e->getMessage());
        }
    }
    function updateUserPassword($userId, $hashedPassword): mixed
    {
        try {
            $stmt = $this->db->prepare('UPDATE Users SET password_hash = :password WHERE id = :id');
            return $stmt->execute(['password' => $hashedPassword, 'id' => $userId]);
        } catch (PDOException $e) {
            throw new Exception("Erreur de base de données : " . $e->getMessage());
        }
    }
    public function deleteUser($userId): bool
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
            return $stmt->execute(['id' => $userId]);
        } catch (PDOException $e) {
            throw new Exception("Erreur de base de données : " . $e->getMessage());
        }
    }
    // Déconnexion de l'utilisateur
    public function clearSession(): void
    {
        session_start();
        session_unset();
        session_destroy();
        return;
    }
}
