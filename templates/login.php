<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital@1&display=swap" rel="stylesheet">

</head>

<body class="bg-[#b7cdd8] min-h-screen flex items-center justify-center">

    <div class="bg-[#e9e6e3] w-full max-w-md p-10 rounded-2xl shadow-xl">

        <!-- Logo -->
        <div class="text-center mb-8">
            <h1 class="text-3xl text-[#7b3f3f]" style="font-family: 'Playfair Display', serif;">
                Name App
            </h1>
            <p class="text-gray-500 text-sm mt-2">
                sing in to your account
            </p>
        </div>

        <!-- Error -->
        <?php if (!empty($error)) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/login" class="space-y-5">

            <!-- Email -->
            <div>
                <label class="block mb-2 text-gray-700">Email</label>
                <input type="text" name="user_name" required
                    placeholder="Enter your Email"
                    class="w-full bg-gray-200 rounded-xl px-4 py-3 
                           focus:outline-none focus:ring-2 focus:ring-purple-300">
            </div>

            <!-- Password -->
            <div>
                <label class="block mb-2 text-gray-700">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                        placeholder="Enter your password"
                        class="w-full bg-gray-200 rounded-xl px-4 py-3 pr-12
                               focus:outline-none focus:ring-2 focus:ring-purple-300">

                    <!-- Toggle -->
                    <button type="button"
                        onclick="togglePassword()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">
                        👁
                    </button>
                </div>
            </div>

            <!-- Button -->
            <button
                class="w-full bg-black text-white py-3 rounded-xl font-semibold
                       hover:bg-gray-800 transition duration-300">
                Sign in
            </button>

        </form>

        <!-- Sign up -->
        <div class="text-center mt-6 text-sm text-gray-600">
            Don’t have an account?
            <a href="/register" class="text-black font-semibold hover:underline">
                Sign up
            </a>
        </div>

    </div>

    <!-- Script -->
    <script>
        function togglePassword() {
            const pass = document.getElementById("password");
            pass.type = pass.type === "password" ? "text" : "password";
        }
    </script>

</body>
</html>