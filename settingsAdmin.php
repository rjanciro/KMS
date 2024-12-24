<?php  
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: index admin.php');
    exit();
}

require_once 'db.php';

// Fetch admin details from database
$stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
$stmt->execute([$_SESSION['email']]);
$admin = $stmt->fetch();

if (!$admin) {
    header('Location: index admin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'edit') {
        $id = $_POST['id'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password !== $confirm_password) {
            echo "<script>alert('Passwords do not match!');</script>";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("UPDATE admins SET email = ?, password = ? WHERE id = ?");
            if ($stmt->execute([$email, $hashedPassword, $id])) {
                $_SESSION['email'] = $email;
                echo "<script>alert('Admin settings updated successfully.');</script>";
            } else {
                echo "<script>alert('Error updating settings.');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #111827;
            color: #e5e5e5;
        }
        .sidebar {
            background-color: #1f2937;
            transition: all 0.3s ease;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 280px;
            border-right: 1px solid #374151;
        }
        
        .sidebar.collapsed {
            width: 5rem;
        }
        
        .sidebar.collapsed .nav-text,
        .sidebar.collapsed .logo-text {
            display: none;
        }
        
        .sidebar.collapsed .logo-container {
            padding: 1rem 0.5rem;
        }
        
        .sidebar.collapsed .logo-container img {
            display: none;
        }
        
        .sidebar.collapsed .hamburger-btn {
            position: absolute;
            right: 50%;
            transform: translateX(50%);
            top: 1rem;
        }
        
        .main-content {
            transition: margin-left 0.3s ease;
            margin-left: 280px;
            padding: 2rem;
            width: calc(100% - 280px);
        }
        
        .main-content.expanded {
            margin-left: 5rem;
            width: calc(100% - 5rem);
        }

        .logo-container {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-bottom: 1px solid #374151;
            position: relative;
            margin-bottom: 1rem;
        }

        .hamburger-btn {
            position: absolute;
            right: 1rem;
            top: 1rem;
            color: #e5e5e5;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2rem;
            height: 2rem;
        }

        .hamburger-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: #9ca3af;
            transition: all 0.3s ease;
            margin: 0.25rem 0.75rem;
            border-radius: 0.5rem;
            gap: 0.75rem;
        }

        .nav-link:hover, .nav-link.active {
            background: #374151;
            color: #ffffff;
        }

        .nav-link i {
            font-size: 1.25rem;
        }

        .form-section, .table-section {
            background: #1f2937;
            padding: 2rem;
            border-radius: 1rem;
            margin-bottom: 2rem;
            border: 1px solid #374151;
        }

        input {
            background: #374151;
            border: 1px solid #4b5563;
            color: #e5e5e5;
            padding: 0.75rem;
            border-radius: 0.5rem;
            width: 100%;
            margin-bottom: 1rem;
        }

        input:focus {
            outline: none;
            border-color: #60a5fa;
            box-shadow: 0 0 0 2px rgba(96, 165, 250, 0.2);
        }

        .button-group {
            display: flex;
            gap: 1rem;
        }

        .button-group button {
            background: #3b82f6;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .button-group button:hover {
            background: #2563eb;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 1rem;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #374151;
        }

        th {
            background: #111827;
            color: #e5e5e5;
            font-weight: 600;
        }

        tr:hover {
            background: #374151;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 280px;
            padding: 1rem;
            border-top: 1px solid #374151;
        }

        .footer a {
            display: block;
            color: #9ca3af;
            text-align: center;
            padding: 0.5rem;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: #ffffff;
        }

        .logo-container {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-bottom: 1px solid #374151;
        }

        .logo-container img {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 0.75rem;
            margin-bottom: 0.75rem;
        }

        h2, h3 {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        label {
            color: #9ca3af;
            display: block;
            margin-bottom: 0.5rem;
        }

        /* Add these styles to fix the sidebar collapse behavior */
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: #9ca3af;
            transition: all 0.3s ease;
            margin: 0.25rem 0.75rem;
            border-radius: 0.5rem;
            gap: 0.75rem;
        }

        .sidebar.collapsed .nav-link {
            padding: 0.75rem 0;
            justify-content: center;
        }

        .sidebar.collapsed .nav-text {
            display: none;
        }

        .sidebar.collapsed .nav-link i {
            margin: 0;
            font-size: 1.25rem;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 280px;
            padding: 1rem;
            border-top: 1px solid #374151;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed .footer {
            width: 5rem;
        }

        .logout-btn {
            background: #ef4444;
            color: white;
            padding: 0.75rem;
            border-radius: 0.5rem;
            width: 100%;
            text-align: center;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .sidebar.collapsed .logout-btn span {
            display: none;
        }

        .sidebar.collapsed .hamburger-btn {
            right: 1.5rem;
            transform: none;
        }

        .sidebar.collapsed .logo-container {
            padding: 3rem 0.5rem 1rem 0.5rem;
            border-bottom: none;
        }
    </style>
</head>
<body class="bg-gray-900">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar">
            <div class="logo-container">
                <img src="pics/kitman.png" alt="logo" class="w-14 h-14 mb-2">
                <span class="logo-text text-white font-semibold">Admin Dashboard</span>
                <button id="hamburger" class="hamburger-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <nav class="mt-6">
                <ul class="space-y-2">
                    <li><a href="admin_page.php" class="nav-link">
                        <i class="fas fa-users-cog"></i>
                        <span class="nav-text">Manage Staff</span>
                    </a></li>
                    <li><a href="grocery_list admin.php" class="nav-link">
                        <i class="fas fa-clipboard-list"></i>
                        <span class="nav-text">View Grocery List</span>
                    </a></li>
                    <li><a href="settingsAdmin.php" class="nav-link active">
                        <i class="fas fa-cog"></i>
                        <span class="nav-text">Settings</span>
                    </a></li>
                </ul>
            </nav>

            <div class="footer">
                <button class="logout-btn" onclick="location.href='actions/logout.php'">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Log Out</span>
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main id="main-content" class="main-content">
            <header class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Settings</h1>
                    <p class="text-gray-400">Manage your account settings</p>
                </div>
            </header>

            <div class="bg-gray-800 rounded-xl p-6 shadow-lg border border-gray-700">
                <form method="post" onsubmit="return confirmPasswordChange()" class="space-y-6">
                    <input type="hidden" name="id" value="<?php echo $_SESSION['user_id'] ?? ''; ?>">
                    
                    <div class="space-y-2">
                        <label class="text-gray-300 text-sm">Email Address</label>
                        <input type="email" name="email" value="<?php echo $_SESSION['email'] ?? ''; ?>" required
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2.5 text-white focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-gray-300 text-sm">New Password</label>
                        <input type="password" name="password" required
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2.5 text-white focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-gray-300 text-sm">Confirm Password</label>
                        <input type="password" name="confirm_password" required
                               class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2.5 text-white focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors">
                    </div>
                    
                    <button type="submit" name="action" value="edit"
                            class="w-full bg-teal-600 hover:bg-teal-700 text-white py-2.5 px-4 rounded-lg transition-colors duration-200">
                        <i class="fas fa-save mr-2"></i>Update Settings
                    </button>
                </form>
            </div>
        </main>
    </div>

    <script>
        function confirmPasswordChange() {
            const password = document.querySelector('input[name="password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
            
            if (password.trim() === "") {
                alert("Password field cannot be empty.");
                return false;
            }
            
            if (password !== confirmPassword) {
                alert("Passwords do not match!");
                return false;
            }
            
            return confirm("Are you sure you want to update your settings?");
        }

        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const hamburger = document.getElementById('hamburger');
        
        function toggleSidebar() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }

        hamburger.addEventListener('click', toggleSidebar);

        document.addEventListener('DOMContentLoaded', () => {
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            }
        });
    </script>
</body>
</html>