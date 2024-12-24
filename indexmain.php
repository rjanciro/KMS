<?php
// Start the session for login functionality
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .role-card {
            transition: all 0.3s ease;
        }
        .role-card:hover {
            transform: translateY(-5px);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #005C63 0%, #008C94 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="w-full max-w-md">
            <!-- Logo and Header -->
            <div class="text-center mb-8">
                <img src="pics/kitman.png" alt="Kitchen Management" class="w-24 h-24 mx-auto mb-4 rounded-full shadow-lg">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back!</h1>
                <p class="text-gray-600">Please select your role to continue</p>
            </div>

            <!-- Role Selection Cards -->
            <div class="space-y-4">
                <form action="index staff.php" method="get">
                    <button class="role-card w-full p-4 bg-white rounded-xl shadow-md hover:shadow-xl flex items-center justify-between group">
                        <div class="flex items-center">
                            <div class="w-12 h-12 gradient-bg rounded-full flex items-center justify-center">
                                <i class="fas fa-user-tie text-white text-xl"></i>
                            </div>
                            <div class="ml-4 text-left">
                                <h3 class="font-semibold text-gray-800">Staff Portal</h3>
                                <p class="text-sm text-gray-500">Access staff dashboard</p>
                            </div>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400 group-hover:text-[#005C63] transition-colors"></i>
                    </button>
                </form>

                <form action="index admin.php" method="get">
                    <button class="role-card w-full p-4 bg-white rounded-xl shadow-md hover:shadow-xl flex items-center justify-between group">
                        <div class="flex items-center">
                            <div class="w-12 h-12 gradient-bg rounded-full flex items-center justify-center">
                                <i class="fas fa-shield-alt text-white text-xl"></i>
                            </div>
                            <div class="ml-4 text-left">
                                <h3 class="font-semibold text-gray-800">Admin Portal</h3>
                                <p class="text-sm text-gray-500">Access admin dashboard</p>
                            </div>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400 group-hover:text-[#005C63] transition-colors"></i>
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center text-sm text-gray-500">
                <p>© 2024 Kitchen Management System. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
