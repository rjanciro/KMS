
<?php  
session_start();
require_once 'db.php'; // Adjusted path

// Check if the admin is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: index.php'); // Redirect to login if not admin
    exit();
}

// Admin actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Insert without password hashing
        $stmt = $pdo->prepare("INSERT INTO staff_logins (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);
        echo "Staff added successfully.";
    } elseif ($action === 'edit') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Update without password hashing
        $stmt = $pdo->prepare("UPDATE staff_logins SET name = ?, email = ?, password = ? WHERE id = ?");
        $stmt->execute([$name, $email, $password, $id]);
        echo "Staff updated successfully.";
    } elseif ($action === 'delete') {
        $id = $_POST['id'];

        $stmt = $pdo->prepare("DELETE FROM staff_logins WHERE id = ?");
        $stmt->execute([$id]);
        echo "Staff deleted successfully.";
    }
}

// Fetch staff logins
$stmt = $pdo->query("SELECT name, email, time, date FROM staff_logins ORDER BY date DESC");
$staff_logins = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="styles/grocery.css">
</head>
<body>
    <div class="main-container">

        <header>
      <h1>StockWise Kitchen Management System</h1>
      <h2>Admin Pagee</h2>
    </header>
    <div class="content">
    <div class="form-section">
    <form method="POST">
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <div class="button-group">
        <button type="submit" name="action" value="add">Add</button>
        <button type="submit" name="action" value="edit">Edit</button>
        <button type="submit" name="action" value="delete">Delete</button>
        </div>
    </form>

    </div>
    <div class="table-section">
    <h2>Staff Logins</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Time</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($staff_logins as $log): ?>
                <tr>
                    <td><?php echo htmlspecialchars($log['name']); ?></td>
                    <td><?php echo htmlspecialchars($log['email']); ?></td>
                    <td><?php echo htmlspecialchars($log['time']); ?></td>
                    <td><?php echo htmlspecialchars($log['date']); ?></td>
                </tr>
            <?php endforeach; ?>
            </div>

        </tbody>
    </table>
    </div>
    <button class="logout-btn" onclick="location.href='actions/logout.php'">Log Out</button>
  </div>
</body>
</html>
