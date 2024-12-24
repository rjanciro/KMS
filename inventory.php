<?php 
session_start();

// Database connection
require_once 'db.php';

// Handle inventory actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add') {
        $item = $_POST['item'];
        $quantity = $_POST['quantity'];
        $expiration_date = $_POST['expiration_date'];
        
        // Determine status automatically based on quantity
        $status = '';
        if ($quantity == 0) {
            $status = 'Out of Stock';
        } elseif ($quantity <= 5) {
            $status = 'Low Stock';
        } elseif ($quantity > 20) {
            $status = 'High Stock';
        } else {
            $status = 'In Stock';
        }

        $stmt = $pdo->prepare("INSERT INTO grocery_list (item, quantity, status, expiration_date) VALUES (?, ?, ?, ?)");
        $stmt->execute([$item, $quantity, $status, $expiration_date]);

        header('Location: inventory.php?success=item_added');
        exit();
    

    } elseif ($action === 'edit') {
        $itemId = $_POST['itemId'];
        $item = $_POST['item'];
        $quantity = $_POST['quantity'];
        $expiration_date = $_POST['expiration_date'];
        
        // Determine status automatically based on quantity
        $status = '';
        if ($quantity == 0) {
            $status = 'Out of Stock';
        } elseif ($quantity <= 5) {
            $status = 'Low Stock';
        } elseif ($quantity > 20) {
            $status = 'High Stock';
        } else {
            $status = 'In Stock';
        }

        $stmt = $pdo->prepare("UPDATE grocery_list SET item = ?, quantity = ?, status = ?, expiration_date = ? WHERE id = ?");
        $stmt->execute([$item, $quantity, $status, $expiration_date, $itemId]);

        header('Location: inventory.php?success=item_updated');
        exit();

        

    } elseif ($action === 'delete') {
        $itemId = $_POST['itemId'];

        $stmt = $pdo->prepare("DELETE FROM grocery_list WHERE id = ?");
        $stmt->execute([$itemId]);
        header('Location: inventory.php?success=item_deleted');
        exit();
    }
}
// Search functionality
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $pdo->prepare("SELECT * FROM grocery_list WHERE item LIKE ?");
    $searchParam = "%" . $search . "%";
    $stmt->execute([$searchParam]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $pdo->query("SELECT * FROM grocery_list");
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff | Inventory</title>
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

        .nav-container {
            flex: 1;
        }

        .logout-container {
            margin-top: auto;
            padding: 1rem;
            border-top: 1px solid rgba(175, 251, 252, 0.1);
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
            
            <div class="nav-container">
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
            </div>

            <div class="logout-container">
                <button onclick="location.href='actions/logout.php'" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="nav-text">Log Out</span>
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main id="main-content" class="main-content">
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Manage Inventory</h1>
                
                <!-- Form Section -->
                <form method="POST" action="inventory.php">
                    <input type="hidden" name="action" value="add">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Item Name</label>
                            <input type="text" name="item" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter item name" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                            <input type="number" name="quantity" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter quantity" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expiration Date</label>
                            <input type="date" name="expiration_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex gap-4 mb-8">
                        <button type="submit" class="flex-1 bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                            <i class="fas fa-plus mr-2"></i> Add Item
                        </button>
                    </div>
                </form>

                <!-- Table Section -->
                <div class="overflow-x-auto bg-white rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiration Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            if (count($result) > 0) {
                                foreach ($result as $row) {
                                    echo "<tr class='hover:bg-gray-50'>
                                            <td class='px-6 py-4 whitespace-nowrap'>{$row['id']}</td>
                                            <td class='px-6 py-4 whitespace-nowrap'>{$row['item']}</td>
                                            <td class='px-6 py-4 whitespace-nowrap'>{$row['quantity']}</td>
                                            <td class='px-6 py-4 whitespace-nowrap'>{$row['status']}</td>
                                            <td class='px-6 py-4 whitespace-nowrap'>{$row['expiration_date']}</td>
                                            <td class='px-6 py-4 whitespace-nowrap'>
                                                <form method='POST' action='inventory.php' class='inline'>
                                                    <input type='hidden' name='action' value='delete'>
                                                    <input type='hidden' name='itemId' value='{$row['id']}'>
                                                    <button type='submit' class='text-red-600 hover:text-red-900'>
                                                        <i class='fas fa-trash'></i>
                                                    </button>
                                                </form>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='px-6 py-4 text-center text-gray-500'>No items found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Include the sidebar toggle functionality
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
