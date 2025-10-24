<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Super Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">

    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6 text-center">Super Admin Registration</h1>

        <form method="POST" action="{{ route('register.superadmin') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition">
                Register
            </button>
        </form>
    </div>

</body>
</html>
