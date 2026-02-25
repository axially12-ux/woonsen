<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>คำร้องขอ</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-200 min-h-screen flex">

<!-- Sidebar -->
<aside class="w-64 bg-[#cfe2f3] p-6 flex flex-col justify-between rounded-r-3xl shadow-xl">

    <div>
        <h2 class="text-3xl font-semibold mb-10 text-purple-700">
            Name App
        </h2>

        <nav class="space-y-6 text-gray-600 text-lg">
            <a href="/home" class="block hover:text-purple-600">📌 กิจกรรม</a>
            <a href="/activity-create" class="block hover:text-purple-600">➕ สร้างกิจกรรม</a>
            <a href="/event-requests" class="block hover:text-purple-600">📥 คำขอเข้าร่วม</a>
            <a href="/account" class="block hover:text-purple-600">👤 บัญชีของฉัน</a>
        </nav>
    </div>

    <a href="/logout"
       class="bg-purple-500 text-white py-3 px-4 rounded-xl text-center hover:bg-purple-600 shadow-lg transition">
        ออกจากระบบ
    </a>
</aside>



<!-- Main Content -->
<div class="flex-1 p-10">

    <!-- Header -->
    <div class="bg-blue-200 rounded-2xl p-6 mb-6 shadow">
        <h1 class="text-3xl text-center text-red-500 font-semibold">
            คำร้องขอ
        </h1>
    </div>

    <!-- Top bar -->
    <div class="flex justify-between items-center mb-6">
        <a href="/home"
           class="bg-blue-300 hover:bg-blue-400 px-4 py-2 rounded-xl shadow">
            ← กลับ
        </a>

        <div class="bg-gray-100 px-4 py-2 rounded-xl shadow">
            จำนวน <?= count($requests) ?> คน
        </div>
    </div>

<?php if (!empty($requests)): ?>

    <div class="bg-gray-100 p-6 rounded-2xl shadow-inner
                grid grid-cols-1 md:grid-cols-2 gap-6
                max-h-[500px] overflow-y-auto">

        <?php foreach ($requests as $req): ?>

            <div class="bg-blue-100 border border-gray-400 rounded-xl p-4 shadow">

                <div class="text-sm text-gray-700 space-y-1">
                    <p><strong>กิจกรรม :</strong>
                        <?= htmlspecialchars($req['event_name']) ?>
                    </p>

                    <p><strong>ชื่อผู้สมัคร :</strong>
                        <?= htmlspecialchars($req['username']) ?>
                    </p>

                    <p><strong>วันที่สมัคร :</strong>
                        <?= date('d/m/Y H:i', strtotime($req['registration_date'])) ?>
                    </p>
                </div>

                <div class="flex justify-end gap-3 mt-4">

                    <form method="POST" action="/approve">
                        <input type="hidden" name="reg_id"
                               value="<?= $req['reg_id'] ?>">
                        <button
                            class="bg-green-400 hover:bg-green-500
                                   text-white px-4 py-1 rounded-full shadow">
                            อนุมัติ
                        </button>
                    </form>

                    <form method="POST" action="/reject">
                        <input type="hidden" name="reg_id"
                               value="<?= $req['reg_id'] ?>">
                        <button
                            class="bg-red-400 hover:bg-red-500
                                   text-white px-4 py-1 rounded-full shadow">
                            ปฏิเสธ
                        </button>
                    </form>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

<?php else: ?>

    <div class="bg-white p-16 rounded-2xl shadow text-center">
        <h2 class="text-xl text-gray-600">
            ไม่มีคำร้องขอ
        </h2>
    </div>

<?php endif; ?>

</div>

</body>
</html>