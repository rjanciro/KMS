<?php
// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'kitchen_management';

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add new item
if (isset($_POST['add'])) {
    $item = $_POST['item'];
    $quantity = $_POST['quantity'];
    $status = $_POST['status'];

    $sql = "INSERT INTO grocery_list (item, quantity, status) VALUES ('$item', '$quantity', '$status')";
    $conn->query($sql);
}

// Edit an item
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $item = $_POST['item'];
    $quantity = $_POST['quantity'];
    $status = $_POST['status'];

    $sql = "UPDATE grocery_list SET item='$item', quantity='$quantity', status='$status' WHERE id='$id'";
    $conn->query($sql);
}

// Delete an item
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM grocery_list WHERE id='$id'";
    $conn->query($sql);
}

// Reset form
if (isset($_POST['reset'])) {
    $_POST = array();
}

// Fetch items for the table
$sql = "SELECT * FROM grocery_list";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockWise Kitchen Management</title>
    <link rel="stylesheet" href="styles/grocery.css">
</head>
<body>
<div class="main-container">
    <h1>StockWise Kitchen Management System</h1>
    <h2>Grocery List</h2>

    <div class="content">
    <div class="form-section">
    <!-- Form for Add/Edit/Delete -->
    <form method="POST" action="">
        <label for="item">Item:</label>
        <input type="text" name="item" required>
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" required>
        <label for="status">Status:</label>
        <input type="text" name="status" required>
        <input type="hidden" name="id" id="id">

        <div class="button-group">
        <button type="submit" name="add">Add</button>
        <button type="submit" name="edit">Edit</button>
        <button type="submit" name="delete">Delete</button>
        <button type="submit" name="reset">Reset</button>
        </div>
    </form>
    </div>

    <div class="table-section">
    <!-- Grocery List Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['item']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    </div>
    </div>
    <button class="logout-btn" onclick="location.href='actions/logout.php'">Log Out</button>
    </div>
</body>
</html>
