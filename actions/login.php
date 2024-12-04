<?php
session_start();
require_once '../db.php'; // Make sure this file contains the correct database connection setup

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginType = $_POST['login_type']; // admin or staff
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($loginType === 'admin') {
        // Fetch admin credentials
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch();

        if ($admin && $password === $admin['password']) { // Plain text password check
            $_SESSION['logged_in'] = true;
            $_SESSION['user_type'] = 'admin';
            header('Location: ../admin_page.php');
            exit();
        } else {
            echo "Invalid admin credentials.";
        }
    } elseif ($loginType === 'staff') {
        // Fetch staff credentials
        $stmt = $pdo->prepare("SELECT * FROM staff_logins WHERE email = ?");
        $stmt->execute([$email]);
        $staff = $stmt->fetch();

        if ($staff && $password === $staff['password']) { // Plain text password check
            $_SESSION['logged_in'] = true;
            $_SESSION['user_type'] = 'staff';

            // Save login timestamp
            $currentTime = date('H:i:s');
            $currentDate = date('Y-m-d');
            $updateStmt = $pdo->prepare("UPDATE staff_logins SET time = ?, date = ? WHERE email = ?");
            $updateStmt->execute([$currentTime, $currentDate, $email]);

            header('Location: ../dashboard.php');
            exit();
        } else {
            echo "Invalid staff credentials.";
        }
    } else {
        echo "Invalid login type.";
    }
}
?>
