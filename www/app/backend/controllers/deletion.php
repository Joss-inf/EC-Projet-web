<?php
// Include necessary files and initialize database connection
require_once '../config/database.php';
require_once '../request/User.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the request (e.g., from a form or query parameter)
    if (isset($_SESSION['user']['id'])) {
        $userId = intval($_SESSION['user']['id']);

        // Create an instance of the User class
        $user = new User(Database::getConnection());

        // Attempt to delete the user
        if ($user->deleteUser($userId)) {
            echo "User account deleted successfully.";
        } else {
            echo "Failed to delete user account.";
        }
    } else {
        echo "User ID is required.";
    }
} else {
    echo "Invalid request method.";
}
?>