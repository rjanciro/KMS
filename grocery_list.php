<?php 
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['logged_in']) || !in_array($_SESSION['user_type'], ['staff', 'admin'])) {
    header('Location: ../index staff.php'); // Redirect to login if not logged in
    exit();
}

// Include the database connection
require_once 'db.php';

// Handle search and filter functionality
$search = '';
$filter = $_GET['filter'] ?? 'all'; // Default to 'all' filter if not provided

try {
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
        $stmt = $pdo->prepare("SELECT * FROM grocery_list WHERE item LIKE ?");
        $searchParam = "%" . $search . "%";
        $stmt->execute([$searchParam]);
    } else {
        switch ($filter) {
            case 'in_stock':
                $stmt = $pdo->prepare("SELECT * FROM grocery_list WHERE quantity > 0");
                break;
            case 'no_stock':
                $stmt = $pdo->prepare("SELECT * FROM grocery_list WHERE quantity = 0");
                break;
            case 'low_stock':
                $stmt = $pdo->prepare("SELECT * FROM grocery_list WHERE quantity > 0 AND quantity <= 5");
                break;
            case 'high_stock':
                $stmt = $pdo->prepare("SELECT * FROM grocery_list WHERE quantity > 20");
                break;
            case 'near_to_expire':
                $stmt = $pdo->prepare("SELECT * FROM grocery_list WHERE expiration_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)");
                break;
            case 'expired':
                $stmt = $pdo->prepare("SELECT * FROM grocery_list WHERE expiration_date < CURDATE()");
                break;
            case 'all':
            default:
                $stmt = $pdo->prepare("SELECT * FROM grocery_list");
                break;
        }

        
        $stmt->execute();
    }
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff | Grocery List</title>
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
                <a href="grocery_list.php" class="nav-item active">
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
            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">Grocery List</h1>

                <!-- Search Section -->
                <div class="mb-6">
                    <form action="grocery_list.php" method="get" class="flex gap-4">
                        <input type="text" name="search" placeholder="Search items..." 
                               value="<?php echo htmlspecialchars($search); ?>"
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                            <i class="fas fa-search mr-2"></i> Search
                        </button>
                    </form>
                </div>

                <!-- Filter Buttons -->
                <div class="flex flex-wrap gap-3 mb-6">
                    <a href="grocery_list.php?filter=all" 
                       class="px-4 py-2 rounded-lg <?php echo $filter == 'all' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?> transition-colors">
                        All
                    </a>
                    <a href="grocery_list.php?filter=no_stock" 
                       class="px-4 py-2 rounded-lg <?php echo $filter == 'no_stock' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?> transition-colors">
                        No Stock
                    </a>
                    <a href="grocery_list.php?filter=low_stock" 
                       class="px-4 py-2 rounded-lg <?php echo $filter == 'low_stock' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?> transition-colors">
                        Low Stock
                    </a>
                    <a href="grocery_list.php?filter=high_stock" 
                       class="px-4 py-2 rounded-lg <?php echo $filter == 'high_stock' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?> transition-colors">
                        High Stock
                    </a>
                    <a href="grocery_list.php?filter=near_to_expire" 
                       class="px-4 py-2 rounded-lg <?php echo $filter == 'near_to_expire' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?> transition-colors">
                        Near to Expire
                    </a>
                    <a href="grocery_list.php?filter=expired" 
                       class="px-4 py-2 rounded-lg <?php echo $filter == 'expired' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?> transition-colors">
                        Expired
                    </a>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto bg-white rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiration Date</th>
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
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='px-6 py-4 text-center text-gray-500'>No items found</td></tr>";
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
