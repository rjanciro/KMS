<?php  
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'staff') {
    header('Location: ./index staff.php');
    exit();
}

require_once 'db.php';

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
            
            $stmt = $pdo->prepare("UPDATE staff_logins SET email = ?, password = ? WHERE id = ?");
            if ($stmt->execute([$email, $hashedPassword, $id])) {
                $_SESSION['email'] = $email;
                echo "<script>alert('Staff settings updated successfully.');</script>";
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
    <title>Staff | Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            background-color: #005C63;
            transition: all 0.3s ease;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 280px;
            display: flex;
            flex-direction: column;
        }

        .logo-text {
            font-size: 1.1rem;
            color: #AFFBFC;
            text-align: center;
            line-height: 1.2;
        }

        .hamburger-btn {
            position: absolute;
            right: 1rem;
            top: 1rem;
            color: #AFFBFC;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
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
        
        .sidebar {
            background-color: #005C63;
            transition: all 0.3s ease;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 280px;
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
            background-color: #f9fafc;
            min-height: 100vh;
            padding: 2rem;
            width: calc(100% - 280px);
        }
        
        .main-content.expanded {
            margin-left: 5rem;
            width: calc(100% - 5rem);
        }
        
        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #AFFBFC;
            transition: all 0.3s ease;
            border-radius: 0.5rem;
            margin: 0.25rem 0;
            text-decoration: none;
        }
        
        .nav-item:hover {
            background: rgba(175, 251, 252, 0.1);
        }
        
        .logo-container {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .logo-container img {
            width: 3.5rem;
            height: 3.5rem;
            transition: all 0.3s ease;
        }
        
        .logo-text {
            font-size: 1.1rem;
            color: #AFFBFC;
            text-align: center;
            line-height: 1.2;
        }
        
        .hamburger-btn {
            position: absolute;
            right: 1rem;
            top: 1rem;
            color: #AFFBFC;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: all 0.3s ease;
            z-index: 10;
        }
        
        .hamburger-btn:hover {
            background: rgba(175, 251, 252, 0.1);
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

        .logout-btn:hover {
            background: #dc2626;
        }
        

        .logout-btn i {
            font-size: 1.1rem;
        }

        .sidebar.collapsed .nav-item {
            justify-content: center;
            padding: 0.75rem 0;
        }

        .sidebar.collapsed .nav-item i {
            margin: 0;
        }

        .hamburger-btn i {
            font-size: 1.25rem;
            transition: transform 0.3s ease;
        }


        .logout-btn:hover {
            background: #dc2626;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar w-64">
            <div class="logo-container">
                <img src="pics/kitman.png" alt="logo">
                <span class="logo-text">Kitchen Management</span>
                <button id="hamburger" class="hamburger-btn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <nav class="p-4 space-y-2">
                <a href="dashboard.php" class="nav-item">
                    <i class="fas fa-home"></i>
                    <span class="nav-text ml-3">Dashboard</span>
                </a>
            
                <a href="inventory.php" class="nav-item">
                    <i class="fas fa-box"></i>
                    <span class="nav-text ml-3">Manage Inventory</span>
                </a>
                <a href="grocery_list.php" class="nav-item">
                    <i class="fas fa-list"></i>
                    <span class="nav-text ml-3">View Grocery List</span>
                </a>
                <a href="settingsStaff.php" class="nav-item">
                    <i class="fas fa-cog"></i>
                    <span class="nav-text ml-3">Settings</span>
                </a>
            </nav>

            <div class="mt-auto p-4">
                <button onclick="location.href='actions/logout.php'" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="nav-text">Log Out</span>
                </button>
            </div>
        </aside>

        <main class="main-content">
            <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Staff Settings</h2>
                
                <form method="post" onsubmit="return confirmPasswordChange()" class="space-y-6">
                    <input type="hidden" name="id" value="<?php echo $_SESSION['user_id'] ?? ''; ?>">
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="email" name="email" 
                               value="<?php echo $_SESSION['email'] ?? ''; ?>" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" id="password" name="password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" name="action" value="edit"
                                class="w-full bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-colors">
                            Update Settings
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        function confirmPasswordChange() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
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

        // Sidebar toggle functionality
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const hamburger = document.getElementById('hamburger');
        
        function toggleSidebar() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }

        if (hamburger) {
            hamburger.addEventListener('click', toggleSidebar);
        }

        // Restore sidebar state
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