<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../index admin.php');
    exit();
}

require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    // Input sanitization
    $id = $_POST['id'] ?? null;
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($action === 'add') {
        if (empty($name) || empty($email) || empty($password)) {
            die("All fields are required for adding staff.");
        }

        // Get current time and date
        $currentTime = date('H:i:s');
        $currentDate = date('Y-m-d');

        // Insert staff record with time and date
        $stmt = $pdo->prepare("INSERT INTO staff_logins (name, email, password, time, date) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $name, 
            $email, 
            password_hash($password, PASSWORD_BCRYPT),
            $currentTime,
            $currentDate
        ]);
    } elseif ($action === 'edit' && $id) {
        if (empty($name) || empty($email) || empty($password)) {
            die("All fields are required for editing staff.");
        }

        // Update staff record
        $stmt = $pdo->prepare("UPDATE staff_logins SET name = ?, email = ?, password = ? WHERE id = ?");
        $stmt->execute([$name, $email, password_hash($password, PASSWORD_BCRYPT), $id]); // Hash the password
    } elseif ($action === 'delete' && $id) {
        // Delete staff record
        $stmt = $pdo->prepare("DELETE FROM staff_logins WHERE id = ?");
        $stmt->execute([$id]);
    } else {
        die("Invalid action or missing ID.");
    }

    // Redirect to the referring page
    $redirectTo = $_POST['redirect_to'] ?? '../admin_page.php'; // Default to admin page
    header("Location: $redirectTo");
    exit();
} 
?>

