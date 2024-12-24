<?php 
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginType = $_POST['login_type'];
    $email = $_POST['email'] ?? null; // Initialize $email
    $password = $_POST['password'] ?? null; // Initialize $password

    if ($loginType === 'admin') {
        // Check admin credentials
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ? AND password = ?");
        $stmt->execute([$email, $password]); // Ensure passwords are hashed for real projects
        $admin = $stmt->fetch();

        if ($admin) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_type'] = 'admin';
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['email'] = $admin['email'];
            header('Location: ../admin_page.php');
            exit();
        } else {
            header('Location: ../index admin.php?error=Invalid admin credentials');
            exit();
        }
    } elseif ($loginType === 'staff') {
        // Fetch staff credentials
        $stmt = $pdo->prepare("SELECT * FROM staff_logins WHERE email = ?");
        $stmt->execute([$email]);
        $staff = $stmt->fetch();

        if ($staff && password_verify($password, $staff['password'])) { // Use password_verify for hashed passwords
            $_SESSION['logged_in'] = true;
            $_SESSION['user_type'] = 'staff';
            $_SESSION['user_id'] = $staff['id'];
            $_SESSION['email'] = $staff['email'];

            // Save login timestamp
            $currentTime = date('H:i:s');
            $currentDate = date('Y-m-d');
            $updateStmt = $pdo->prepare("UPDATE staff_logins SET time = ?, date = ? WHERE email = ?");
            $updateStmt->execute([$currentTime, $currentDate, $email]);

            header('Location: ../dashboard.php');
            exit();
        } else {
            header('Location: ../index staff.php?error=Invalid staff credentials');
            exit();
        }
    } else {
        header('Location: ../indexmain.php?error=Invalid login type');
        exit();
    }
}
