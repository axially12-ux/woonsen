<?php
// $user
// $myEvents
// $joinedEvents
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-8">

    <div class="mb-6">
        <a href="/home"
            class="inline-flex items-center gap-2 
              bg-gradient-to-r from-blue-500 to-purple-500 
              hover:from-blue-600 hover:to-purple-600
              text-white px-5 py-2 rounded-xl shadow-lg 
              transition duration-300">
            ⬅️ กลับหน้า Home
        </a>
    </div>
    <div class="max-w-6xl mx-auto">


        <!-- ================= USER INFO ================= -->

        <h1 class="text-3xl font-bold mb-6">👤 ข้อมูลผู้ใช้</h1>

        <div class="bg-white p-6 rounded-xl shadow mb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <p><strong>User ID:</strong> <?= htmlspecialchars($user['user_id'] ?? '-') ?></p>
                <p><strong>Username:</strong> <?= htmlspecialchars($user['user_name'] ?? '-') ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? '-') ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($user['tel'] ?? '-') ?></p>
                <p><strong>County:</strong> <?= htmlspecialchars($user['county'] ?? '-') ?></p>
                <p><strong>Job:</strong> <?= htmlspecialchars($user['job'] ?? '-') ?></p>
                <p><strong>Birthday:</strong> <?= htmlspecialchars($user['birthday'] ?? '-') ?></p>
                <p><strong>Gender:</strong> <?= htmlspecialchars($user['gender'] ?? '-') ?></p>

            </div>
        </div>


        <!-- ================= CREATED EVENTS ================= -->

        <h2 class="text-2xl font-bold mb-6">📁 กิจกรรมที่ฉันสร้าง</h2>

        <?php if (!empty($myEvents)): ?>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">

                <?php foreach ($myEvents as $event): ?>

                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1">

                        <!-- รูปกิจกรรม -->
                        <?php if (!empty($event['path'])): ?>
                            <img src="/uploads/<?= htmlspecialchars($event['path']) ?>"
                                class="w-full h-48 object-cover">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/400x200"
                                class="w-full h-48 object-cover">
                        <?php endif; ?>

                        <div class="p-4">

                            <h3 class="text-lg font-bold mb-2">
                                <?= htmlspecialchars($event['event_name'] ?? '-') ?>
                            </h3>

                            <p class="text-gray-600 text-sm mb-4">
                                <?= htmlspecialchars($event['event_detail'] ?? '') ?>
                            </p>

                            <div class="flex justify-between">

                                <a href="/edit-event?id=<?= $event['event_id'] ?>"
                                    class="bg-yellow-400 px-3 py-1 rounded">
                                    แก้ไข
                                </a>

                                <a href="/delete-event?id=<?= $event['event_id'] ?>"
                                    onclick="return confirm('ต้องการลบกิจกรรมนี้หรือไม่?')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                    ลบ
                                </a>

                            </div>

                        </div>
                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>
            <div class="bg-white p-4 rounded shadow mb-12">
                ยังไม่มีการสร้างกิจกรรม
            </div>
        <?php endif; ?>


        <!-- ================= JOINED EVENTS ================= -->

        <h2 class="text-2xl font-bold mb-6">🎯 กิจกรรมที่ฉันเข้าร่วม</h2>

        <?php if (!empty($joinedEvents)): ?>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <?php foreach ($joinedEvents as $event): ?>

                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300">

                        <?php if (!empty($event['image'])): ?>
                            <img src="/uploads/<?= htmlspecialchars($event['image']) ?>"
                                class="w-full h-48 object-cover">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/400x200"
                                class="w-full h-48 object-cover">
                        <?php endif; ?>

                        <div class="p-4">

                            <h3 class="text-lg font-bold mb-2">
                                <?= htmlspecialchars($event['event_name'] ?? '-') ?>
                            </h3>

                            <p class="text-gray-600 text-sm">
                                <?= htmlspecialchars($event['event_detail'] ?? '') ?>
                            </p>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php else: ?>
            <div class="bg-white p-4 rounded shadow">
                ยังไม่ได้เข้าร่วมกิจกรรม
            </div>
        <?php endif; ?>


    </div>

</body>

</html>