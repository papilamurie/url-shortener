<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener - Shorten Your Links</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #0a0a0f 0%, #1a1a24 100%);
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        .glow-card {
            background: rgba(26, 26, 36, 0.8);
            border: 1px solid rgba(14, 165, 233, 0.2);
            backdrop-filter: blur(10px);
        }
        .glow-card:hover {
            border-color: rgba(14, 165, 233, 0.5);
            box-shadow: 0 0 30px rgba(14, 165, 233, 0.2);
        }
        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9, #22d3ee);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(14, 165, 233, 0.3);
        }
        .btn-secondary {
            background: transparent;
            border: 2px solid #0ea5e9;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background: rgba(14, 165, 233, 0.1);
        }
    </style>
</head>
<body class="flex items-center justify-center p-6">
    <div class="max-w-2xl w-full">
        <!-- Icon -->
        <div class="text-center mb-8">
            <div class="inline-block text-8xl mb-4 animate-bounce">🔗</div>
        </div>

        <!-- Card -->
        <div class="glow-card rounded-2xl p-8 md:p-12 text-center transition-all duration-300">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                URL Shortener
            </h1>
            <p class="text-xl text-gray-300 mb-8">
                Transform long URLs into short, shareable links
            </p>

            <!-- Features -->
            <div class="grid md:grid-cols-3 gap-4 mb-10 text-left">
                <div class="bg-black bg-opacity-30 rounded-lg p-4">
                    <div class="text-2xl mb-2">⚡</div>
                    <h3 class="text-white font-semibold mb-1">Fast</h3>
                    <p class="text-gray-400 text-sm">Instant URL shortening</p>
                </div>
                <div class="bg-black bg-opacity-30 rounded-lg p-4">
                    <div class="text-2xl mb-2">📊</div>
                    <h3 class="text-white font-semibold mb-1">Analytics</h3>
                    <p class="text-gray-400 text-sm">Track your link clicks</p>
                </div>
                <div class="bg-black bg-opacity-30 rounded-lg p-4">
                    <div class="text-2xl mb-2">🔒</div>
                    <h3 class="text-white font-semibold mb-1">Secure</h3>
                    <p class="text-gray-400 text-sm">Safe and reliable</p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('login') }}" class="btn-primary text-white font-semibold py-3 px-8 rounded-lg">
                    Login
                </a>
                <a href="{{ route('register') }}" class="btn-secondary text-blue-400 font-semibold py-3 px-8 rounded-lg">
                    Create Account
                </a>
            </div>

            <!-- Footer Note -->
            <p class="text-gray-500 text-sm mt-8">
                Made with Laravel by <a href="https://blog-system-main-mohnwe.laravel.cloud" class="text-blue-400 hover:text-blue-300">Daniel Oluikpe</a>
            </p>
        </div>
    </div>
</body>
</html>
