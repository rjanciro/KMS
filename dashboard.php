<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: index.php');
    exit();
}

require_once 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Grocery List Management</title>
  <link rel="stylesheet" href="styles/grocery.css">
</head>
<body>
  <div class="main-container">
    <header>
      <h1>StockWise Kitchen Management System</h1>
      <h2>GROCERY LIST</h2>
    </header>
    <div class="content">
      <div class="form-section">
        <h3>STOCK LEVEL</h3>
        <form action="actions/manage_items.php" method="post">
    <input type="hidden" name="itemId" id="itemId" value=""> <!-- Add this -->
    <label for="item">Item:</label>
    <input type="text" name="item" id="item" placeholder="Enter item" required>
    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" id="quantity" placeholder="Enter quantity" required>
    <label for="status">Status:</label>
    <input type="text" name="status" id="status" placeholder="Enter status" required>
    <div class="button-group">
        <button type="submit" name="action" value="add">Add</button>
        <button type="submit" name="action" value="edit">Edit</button>
        <button type="submit" name="action" value="delete">Delete</button>
        <button type="reset">Reset</button>
    </div>
</form>

      </div>
      <div class="table-section">
        <table>
          <thead>
            <tr>
              <th>ItemID</th>
              <th>Item</th>
              <th>Quantity</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM grocery_list");
            while ($row = $stmt->fetch()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['item']}</td>
                        <td>{$row['quantity']}</td>
                        <td>{$row['status']}</td>
                      </tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
    <button class="logout-btn" onclick="location.href='actions/logout.php'">Log Out</button>
  </div>

  <script>
    document.querySelectorAll('table tbody tr').forEach(row => {
        row.addEventListener('click', () => {
            document.getElementById('itemId').value = row.cells[0].innerText; // Set itemId
            document.getElementById('item').value = row.cells[1].innerText; // Set item name
            document.getElementById('quantity').value = row.cells[2].innerText; // Set quantity
            document.getElementById('status').value = row.cells[3].innerText; // Set status
        });
    });
</script>
</body>
</html>


