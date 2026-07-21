<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Room Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md mx-4">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">RoomBook</h1>
            <p class="text-gray-500 mt-2">Sign in to manage your bookings</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-8">
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    @foreach($errors->all() as $error)
                        <p class="text-sm text-red-600">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="/login" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="you@company.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="********">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600">
                        <span class="text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                    Sign In
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-4">
                Don't have an account? <a href="/register" class="text-blue-600 hover:text-blue-800">Register</a>
            </p>
        </div>

        <!-- Demo Credentials -->
        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-sm font-medium text-blue-800 mb-2">Demo Accounts:</p>
            <div class="text-xs text-blue-700 space-y-1">
                <p><strong>Admin:</strong> admin@example.com / password</p>
                <p><strong>User:</strong> user@example.com / password</p>
            </div>
        </div>
    </div>
</body>
</html>
