<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link rel="stylesheet" href="styles/login.css">
</head>
<body>
  <div class="main-container">
    <h1>Welcome,</h1>

    <!-- Admin Login -->
    <div class="login-box">
      <h2>ADMIN LOG IN</h2>
      <form action="actions/login.php" method="post">
        <label for="admin-email">Email:</label>
        <input type="email" name="email" id="admin-email" placeholder="Enter your email" required>
        <label for="admin-password">Password:</label>
        <input type="password" name="password" id="admin-password" placeholder="Enter your password" required>
        <button type="submit" name="login_type" value="admin">LOG IN</button>
      </form>
    </div>

    <!-- Staff Login -->
    <div class="login-box">
      <h2>STAFF LOG IN</h2>
      <form action="actions/login.php" method="post">
        <label for="staff-email">Email:</label>
        <input type="email" name="email" id="staff-email" placeholder="Enter your email" required>
        <label for="staff-password">Password:</label>
        <input type="password" name="password" id="staff-password" placeholder="Enter your password" required>
        <button type="submit" name="login_type" value="staff">LOG IN</button>
      </form>
    </div>
  </div>
</body>
</html>
