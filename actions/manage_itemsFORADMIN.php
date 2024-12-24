<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../index admin.php');
    exit();
}

require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $item = $_POST['item'];
        $quantity = $_POST['quantity'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("INSERT INTO grocery_list (item, quantity, status) VALUES (?, ?, ?)");
        $stmt->execute([$item, $quantity, $status]);

        header('Location: ../admin_page.php');
        exit();
    } elseif ($action === 'edit') {
        $itemId = $_POST['itemId']; // Add a hidden input field for ItemID in the form to edit items.
        $item = $_POST['item'];
        $quantity = $_POST['quantity'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("UPDATE grocery_list SET item = ?, quantity = ?, status = ? WHERE id = ?");
        $stmt->execute([$item, $quantity, $status, $itemId]);

        header('Location: ../admin_page.php');
        exit();
    } elseif ($action === 'delete') {
        $itemId = $_POST['itemId']; // Add a hidden input field for ItemID in the form to delete items.

        $stmt = $pdo->prepare("DELETE FROM grocery_list WHERE id = ?");
        $stmt->execute([$itemId]);

        header('Location: ../admin_page.php');
        exit();
    }
}
?>
