<?php
session_start();

// If not logged in, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Include the database connection
include 'db.php';

// Handle add, edit, delete actions (you can implement these according to your needs)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'];
  var_dump($_POST); // Debugging: Check if the form data is coming through

  if ($action === 'add') {
      // Your add code
  } elseif ($action === 'edit') {
      // Your edit code
  } elseif ($action === 'delete') {
      // Your delete code
  }
}


$query = "SELECT * FROM grocery_list";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Grocery List - StockWise Kitchen Management</title>
  <link rel="stylesheet" href="grocery.css">
</head>
<body>
  <div class="main-container">
    <h1>Manage Grocery List</h1>

    <form action="actions/manage_items.php" method="post">
    <input type="hidden" name="itemId" id="itemId" value=""> <!-- Add this -->
    <label for="item">Item:</label>
    <input type="text" name="item" id="item" placeholder="Enter item" required>
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" placeholder="Enter quantity" required>
    <label for="status">Status:</label>
    <input type="text" name="status" id="status" placeholder="Enter status" required>
    <div class="button-group">
      <!-- Form for adding/editing groceries -->
      <input type="text" name="item" placeholder="Item" required>
      <input type="number" name="quantity" placeholder="Quantity" required>
      <input type="text" name="status" placeholder="Status" required>
      <button type="submit" class="btn">Add Grocery</button>
    </form>

    <h2>Current Grocery List</h2>
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
</body>
</html>
