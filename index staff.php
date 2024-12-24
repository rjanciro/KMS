<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login - Kitchen Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #005C63 0%, #008C94 100%);
        }
        .input-icon {
            color: #9CA3AF;
        }
        .form-input:focus + .input-icon {
            color: #005C63;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Login Card -->
        <div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-xl p-8 space-y-6">
            <!-- Logo and Header -->
            <div class="text-center space-y-2">
                <img src="pics/kitman.png" alt="Kitchen Management" 
                     class="w-20 h-20 mx-auto mb-2 rounded-xl shadow-lg transform hover:scale-105 transition-transform duration-300">
                <h1 class="text-2xl font-bold text-gray-800">Welcome Back!</h1>
                <p class="text-gray-500">Please login to your staff account</p>
            </div>

            <!-- Login Form -->
            <form action="actions/login.php" method="POST" class="space-y-5">
                <input type="hidden" name="login_type" value="staff">
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="relative group">
                        <input type="email" id="email" name="email" required
                               class="block w-full pl-11 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 bg-gray-50 focus:bg-white"
                               placeholder="Enter your email">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope input-icon group-focus-within:text-teal-500 transition-colors duration-300"></i>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="relative group">
                        <input type="password" id="password" name="password" required
                               class="block w-full pl-11 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 bg-gray-50 focus:bg-white"
                               placeholder="Enter your password">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock input-icon group-focus-within:text-teal-500 transition-colors duration-300"></i>
                        </div>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full py-3 px-4 bg-gradient-to-r from-teal-600 to-teal-700 text-white rounded-lg hover:from-teal-700 hover:to-teal-800 focus:ring-4 focus:ring-teal-500 focus:ring-opacity-50 transition-all duration-300 font-medium transform hover:scale-[1.02] active:scale-[0.98]">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Login
                </button>
            </form>

            <!-- Back Button -->
            <div class="text-center">
                <a href="indexmain.php" 
                   class="inline-flex items-center text-sm text-gray-500 hover:text-teal-600 transition-colors group">
                    <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
                    Back to Role Selection
                </a>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-white/80 text-sm mt-6">
            © 2024 Kitchen Management System. All rights reserved.
        </p>
    </div>

    <script>
        // Optional: Add loading state to form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i>Logging in...';
        });
    </script>
</body>
</html>
