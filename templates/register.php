<?php
$error = $error ?? null;
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Create Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-200 min-h-screen flex justify-center items-center">

<div class="bg-white w-full max-w-5xl p-10 rounded-2xl shadow-xl">

    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-serif text-red-700 italic">Name App</h1>
        <p class="text-gray-500 mt-2">Create your account</p>
    </div>

    <?php if (!empty($error)) : ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="/register">

        <!-- ใช้ 6 columns -->
        <div class="grid grid-cols-6 gap-6">

            <!-- Name (ครึ่งซ้าย) -->
            <div class="col-span-3">
                <label class="block mb-2">Name</label>
                <input type="text" name="user_name" required
                    placeholder="Enter your name"
                    class="w-full bg-gray-100 rounded-full px-5 py-3 outline-none focus:ring-2 focus:ring-black">
            </div>

            <!-- Email (ครึ่งขวา) -->
            <div class="col-span-3">
                <label class="block mb-2">Email</label>
                <input type="email" name="email" required
                    placeholder="Enter your email"
                    class="w-full bg-gray-100 rounded-full px-5 py-3 outline-none focus:ring-2 focus:ring-black">
            </div>

            <!-- Phone -->
            <div class="col-span-2">
                <label class="block mb-2">Phone Number</label>
                <input type="text" name="tel" required
                    placeholder="Enter your phone number"
                    class="w-full bg-gray-100 rounded-full px-5 py-3 outline-none focus:ring-2 focus:ring-black">
            </div>

            <!-- Birthdate -->
            <div class="col-span-2">
                <label class="block mb-2">Date of Birthday</label>
                <input type="date" name="birthdate" required
                    class="w-full bg-gray-100 rounded-full px-5 py-3 outline-none focus:ring-2 focus:ring-black">
            </div>

            <!-- Gender -->
            <div class="col-span-2">
                <label class="block mb-2">Gender</label>
                <select name="gender"
                    class="w-full bg-gray-100 rounded-full px-5 py-3 outline-none focus:ring-2 focus:ring-black">
                    <option value="">Select gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <!-- Job (ครึ่งซ้าย) -->
            <div class="col-span-3">
                <label class="block mb-2">Job</label>
                <input type="text" name="job" required
                    placeholder="Enter your job"
                    class="w-full bg-gray-100 rounded-full px-5 py-3 outline-none focus:ring-2 focus:ring-black">
            </div>

            <!-- County (ครึ่งขวา) -->
            <div class="col-span-3">
                <label class="block mb-2">County</label>
                <input type="text" name="county" required
                    placeholder="Select your county"
                    class="w-full bg-gray-100 rounded-full px-5 py-3 outline-none focus:ring-2 focus:ring-black">
            </div>

            <!-- Password -->
            <div class="col-span-3 relative">
                <label class="block mb-2">Password</label>
                <input type="password" name="password" id="password" required
                    placeholder="Enter your password"
                    class="w-full bg-gray-100 rounded-full px-5 py-3 pr-12 outline-none focus:ring-2 focus:ring-black">
                <button type="button"
                    onclick="togglePassword('password')"
                    class="absolute right-4 top-11 text-gray-600">
                    👁
                </button>
            </div>

            <!-- Confirm Password -->
            <div class="col-span-3">
                <label class="block mb-2">Confirm Password</label>
                <input type="password" name="confirm_password" required
                    placeholder="Confirm your password"
                    class="w-full bg-gray-100 rounded-full px-5 py-3 outline-none focus:ring-2 focus:ring-black">
            </div>

        </div>

        <!-- Button -->
        <div class="mt-10">
            <button
                class="w-full bg-black text-white py-4 rounded-full text-lg hover:opacity-90 transition">
                Sign up
            </button>
        </div>

    </form>

    <div class="text-center mt-6 text-gray-600">
        Already have an account?
        <a href="/login" class="font-semibold text-black hover:underline">Sign in</a>
    </div>

</div>

<script>
function togglePassword(id) {
    const input = document.getElementById(id);
    input.type = input.type === "password" ? "text" : "password";
}
</script>

</body>
</html>