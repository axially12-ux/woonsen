<?php
$conn = getConnection();

$search = $_GET['search'] ?? '';
$date   = $_GET['date'] ?? '';
$status = $_GET['status'] ?? 'open';

/* =========================
   Build Query
========================= */
$where = [];

if ($search) {
    $where[] = "event_name LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
}

if ($date) {
    $where[] = "DATE(start) = '" . mysqli_real_escape_string($conn, $date) . "'";
}

if ($status === 'open') {
    $where[] = "end >= CURDATE()";
}

if ($status === 'closed') {
    $where[] = "end < CURDATE()";
}

$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";

$events = mysqli_query($conn, "SELECT * FROM events $whereSQL ORDER BY start DESC");
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>กิจกรรม</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#e6e6e6]">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-[#cfe2f3] p-6 flex flex-col justify-between rounded-r-3xl shadow-xl">

            <div>
                <h2 class="text-3xl italic mb-10 text-gray-700 leading-tight">
                    Name<br>App
                </h2>

                <nav class="space-y-6 text-gray-700 text-lg">
                    <a href="/home" class="flex items-center gap-3 text-purple-600 font-semibold">📌 กิจกรรม</a>
                    <a href="/activity-create" class="flex items-center gap-3 hover:text-purple-600 transition">➕ สร้างกิจกรรม</a>
                    <a href="/event-requests" class="flex items-center gap-3 hover:text-purple-600 transition">📥 คำขอเข้าร่วม</a>
                    <a href="/account" class="flex items-center gap-3 hover:text-purple-600 transition">👤 บัญชีของฉัน</a>
                </nav>
            </div>

            <a href="/logout"
                class="bg-[#9ad0ec] text-gray-700 py-3 px-4 rounded-xl text-center hover:bg-[#7fbfe0] transition shadow">
                🔓 ออกจากระบบ
            </a>
        </aside>

        <!-- Main -->
        <main class="flex-1 p-8">

            <!-- Banner -->
            <div class="h-44 rounded-3xl overflow-hidden mb-8 relative shadow-lg">
                <img src="https://images.unsplash.com/photo-1506157786151-b8491531f063"
                    class="w-full h-full object-cover">
                <h1 class="absolute inset-0 flex items-center justify-center
               text-5xl font-serif text-pink-200 tracking-wide">
                    กิจกรรม
                </h1>
            </div>

            <!-- Filter -->
            <form method="GET" class="flex items-center justify-between mb-8">

                <div class="flex gap-4">
                    <a href="?status=open"
                        class="px-6 py-2 rounded-full shadow-md
           <?= $status === 'open' ? 'bg-pink-400 text-white' : 'bg-pink-200 text-gray-700' ?>">
                        เปิดรับสมัคร
                    </a>

                    <a href="?status=closed"
                        class="px-6 py-2 rounded-full shadow-md
           <?= $status === 'closed' ? 'bg-purple-400 text-white' : 'bg-purple-200 text-gray-700' ?>">
                        ผ่านมาแล้ว
                    </a>
                </div>

                <div class="flex gap-4 items-center">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                        placeholder="ค้นหา"
                        class="px-4 py-2 rounded-full shadow bg-white w-64">

                    <input type="date" name="date" value="<?= htmlspecialchars($date) ?>"
                        class="px-4 py-2 rounded-full shadow bg-white">

                    <button class="bg-purple-400 text-white px-5 py-2 rounded-full shadow">
                        ค้นหา
                    </button>
                </div>
            </form>

            <!-- Grid -->
            <div class="grid grid-cols-4 gap-8">

                <?php if (mysqli_num_rows($events) > 0): ?>
                    <?php while ($event = mysqli_fetch_assoc($events)): ?>

                        <?php
                        // รูป
                        $images = [];
                        $imgResult = mysqli_query($conn, "SELECT path FROM images WHERE event_id=" . $event['event_id']);
                        while ($img = mysqli_fetch_assoc($imgResult)) {
                            $images[] = $img['path'];
                        }

                        // จำนวนคน
                        $count = mysqli_fetch_assoc(mysqli_query(
                            $conn,
                            "SELECT COUNT(*) as total FROM registrations
 WHERE event_id=" . $event['event_id'] . " AND reg_status='approved'"
                        ));
                        $current = $count['total'] ?? 0;

                        // เช็คสถานะ
                        $isClosed = strtotime($event['end']) < strtotime(date('Y-m-d'));
                        $isFull = ($event['max_participants'] != 0 && $current >= $event['max_participants']);
                        ?>

                        <a href="/event?id=<?= $event['event_id'] ?>"
                            class="block bg-white rounded-3xl shadow-lg p-5 hover:shadow-2xl hover:scale-105 transition duration-300">

                            <div class="relative overflow-hidden rounded-2xl mb-4 h-40">
                                <?php if ($images): ?>
                                    <img src="/uploads/<?= htmlspecialchars($images[0]) ?>"
                                        class="w-full h-40 object-cover">
                                <?php else: ?>
                                    <div class="bg-gray-300 w-full h-full rounded-2xl"></div>
                                <?php endif; ?>
                            </div>

                            <h3 class="text-lg font-bold text-purple-700 mb-1">
                                <?= htmlspecialchars($event['event_name']) ?>
                            </h3>

                            <p class="text-sm text-gray-600">
                                <?= date("d M Y", strtotime($event['start'])) ?>
                                -
                                <?= date("d M Y", strtotime($event['end'])) ?>
                            </p>

                            <div class="flex justify-between items-center mt-4">
                                <span class="text-sm text-gray-600">
                                    จำนวน <?= $current ?> / <?= $event['max_participants'] ?>
                                </span>

                                <?php if ($isClosed): ?>
                                    <span class="bg-gray-400 text-white px-4 py-1 rounded-full text-sm shadow">
                                        ปิดรับสมัคร
                                    </span>

                                <?php elseif ($isFull): ?>
                                    <span class="bg-red-400 text-white px-4 py-1 rounded-full text-sm shadow">
                                        เต็มแล้ว
                                    </span>

                                <?php else: ?>
                                    <span class="bg-purple-400 text-white px-4 py-1 rounded-full text-sm shadow">
                                        ขอเข้าร่วม
                                    </span>
                                <?php endif; ?>
                            </div>
                        </a>

                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="col-span-4 text-center text-gray-400 text-lg">
                        ไม่พบกิจกรรม
                    </p>
                <?php endif; ?>

            </div>

            <div class="text-center mt-12 text-gray-400">
                ©2026
            </div>

        </main>
    </div>

</body>

</html>