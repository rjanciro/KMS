<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !in_array($_SESSION['user_type'], ['staff', 'admin'])) {
    header('Location: ../index staff.php');
    exit();
}

require_once 'db.php';

if (!isset($pdo)) {
    die("Database connection failed.");
}

try {
    $totalItemsQuery = $pdo->query("SELECT COUNT(*) AS total_items FROM grocery_list");
    $totalItems = $totalItemsQuery->fetch(PDO::FETCH_ASSOC)['total_items'];

    $lowStockQuery = $pdo->query("SELECT COUNT(*) AS low_stock FROM grocery_list WHERE status = 'low stock'");
    $lowStock = $lowStockQuery->fetch(PDO::FETCH_ASSOC)['low_stock'];

    $totalUsersQuery = $pdo->query("SELECT COUNT(*) AS total_users FROM staff_logins");
    $totalUsers = $totalUsersQuery->fetch(PDO::FETCH_ASSOC)['total_users'];
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .dashboard-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
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
        
        /* Reference inventory.php for sidebar styles */
        /* startLine: 71 */
        /* endLine: 212 */
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

        <!-- Main Content -->
        <main id="main-content" class="main-content">
            <header class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Dashboard Overview</h1>
                <div class="flex items-center space-x-4">
                    <button class="p-2 rounded-full hover:bg-gray-200">
                        <i class="fas fa-bell text-gray-600"></i>
                    </button>
                    <img src="pics/download.png" alt="User" class="w-10 h-10 rounded-full">
                </div>
            </header>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="dashboard-card bg-white rounded-xl p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Low Stock Items</p>
                            <h3 class="text-2xl font-bold text-gray-800"><?php echo $lowStock; ?></h3>
                        </div>
                        <div class="p-3 bg-red-100 rounded-full">
                            <i class="fas fa-exclamation-triangle text-red-500"></i>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card bg-white rounded-xl p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Inventory</p>
                            <h3 class="text-2xl font-bold text-gray-800"><?php echo $totalItems; ?></h3>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="fas fa-box text-blue-500"></i>
                        </div>
                    </div>
                </div>

                <div class="dashboard-card bg-white rounded-xl p-6 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Users</p>
                            <h3 class="text-2xl font-bold text-gray-800"><?php echo $totalUsers; ?></h3>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fas fa-users text-green-500"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl p-6 shadow-sm mt-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h2>
                <div class="flex justify-between items-center gap-4">
                    <a href="inventory.php" class="flex-1 p-4 text-center rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-plus-circle text-blue-500 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600">Add Item</p>
                    </a>
                    <a href="grocery_list.php" class="flex-1 p-4 text-center rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-list-alt text-green-500 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600">View List</p>
                    </a>
                    <a href="settingsStaff.php" class="flex-1 p-4 text-center rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-cog text-gray-500 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600">Settings</p>
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const hamburger = document.getElementById('hamburger');
        
        function toggleSidebar() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }

        hamburger.addEventListener('click', toggleSidebar);

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
