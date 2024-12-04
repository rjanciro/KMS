<?php
// Start the session for login functionality
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StockWise Kitchen Management System</title>
  <link rel="stylesheet" href="styles/login.css">
</head>
<body>
  <div class="main-container">
    <h1>StockWise Kitchen<br>Management System</h1>
    <div class="button-container">
      <form action="index.php" method="get">
        <button class="btn" type="submit" name="action" value="login">LOG IN</button>
      </form>
      <form action="grocery_list.php" method="get">
        <button class="btn" type="submit" name="action" value="grocery_list">GROCERY LIST</button>
      </form>
    </div>
  </div>
</body>
</html>
