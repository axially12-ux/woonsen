<?php 
$conn = getConnection();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) { echo "ไม่พบกิจกรรม"; exit; }

$event = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM events WHERE event_id = $id LIMIT 1")
);
if (!$event) { echo "ไม่พบข้อมูลกิจกรรม"; exit; }

/* ================= รูป ================= */
$images = [];
$result = mysqli_query($conn, "SELECT * FROM images WHERE event_id = $id");
while ($row = mysqli_fetch_assoc($result)) {
    $images[] = $row;
}

/* ================= นับผู้ approved ================= */
$countResult = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) as total 
         FROM registrations 
         WHERE event_id = $id 
         AND reg_status = 'approved'"
    )
);
$currentCount = $countResult['total'] ?? 0;

/* ================= เช็คสถานะกิจกรรม ================= */
$isClosed = strtotime($event['end']) < strtotime(date('Y-m-d'));
$isFull = ($event['max_participants'] != 0 && 
           $currentCount >= $event['max_participants']);

/* ================= เช็คสถานะผู้ใช้ ================= */
$user_id = $_SESSION['user_id'] ?? 0;
$joinStatus = null;

if ($user_id > 0) {
    $checkJoin = mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT reg_status 
             FROM registrations 
             WHERE event_id = $id 
             AND user_id = $user_id"
        )
    );
    if ($checkJoin) $joinStatus = $checkJoin['reg_status'];
}

/* ================= ดึงรายชื่อ (เฉพาะ approved) ================= */
$approvedUsers = [];
if ($joinStatus === 'approved') {
    $approvedQuery = mysqli_query(
        $conn,
        "SELECT u.user_name
         FROM registrations r
         JOIN users u ON r.user_id = u.user_id
         WHERE r.event_id = $id
         AND r.reg_status = 'approved'"
    );
    while ($row = mysqli_fetch_assoc($approvedQuery)) {
        $approvedUsers[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>รายละเอียดกิจกรรม</title>

<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>

<style>
body { font-family: 'Prompt', sans-serif; }
</style>
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200">

<div class="flex min-h-screen">

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

<!-- Main -->
<main class="flex-1 p-10">

<!-- Header -->
<div class="bg-white rounded-3xl p-6 shadow-xl mb-10 flex items-center justify-between">
    <a href="/home"
       class="bg-purple-100 text-purple-700 px-5 py-2 rounded-full shadow hover:bg-purple-200 transition">
        ← กลับ
    </a>

    <h1 class="text-3xl font-semibold text-gray-800">
        <?= htmlspecialchars($event['event_name']) ?>
    </h1>

    <div></div>
</div>

<!-- Slider -->
<div class="rounded-3xl overflow-hidden shadow-2xl mb-10 relative h-[420px] bg-gray-200">

    <?php if (!empty($images)): ?>
        <?php foreach ($images as $index => $img): ?>
            <img src="/uploads/<?= htmlspecialchars($img['path']) ?>"
                 class="w-full h-full object-cover absolute inset-0 slide transition duration-500"
                 style="<?= $index === 0 ? '' : 'display:none;' ?>">
        <?php endforeach; ?>

        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>

        <?php if (count($images) > 1): ?>
            <button onclick="prevSlide()"
                class="absolute left-6 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full w-10 h-10 shadow text-lg">
                ‹
            </button>
            <button onclick="nextSlide()"
                class="absolute right-6 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full w-10 h-10 shadow text-lg">
                ›
            </button>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Ticket Card -->
<div class="relative bg-[#8fc1d4] border border-gray-300 shadow-2xl rounded-3xl p-10 grid grid-cols-3">

    <!-- รูเจาะ -->
    <div class="absolute -top-4 left-2/3 w-10 h-10 bg-gray-100 rounded-full border border-gray-300"></div>
    <div class="absolute -bottom-4 left-2/3 w-10 h-10 bg-gray-100 rounded-full border border-gray-300"></div>

    <!-- เส้นปะ -->
    <div class="absolute top-0 bottom-0 left-2/3 border-l-2 border-dashed border-white"></div>

    <!-- ซ้าย -->
    <div class="col-span-2 pr-10">

        <h2 class="text-2xl font-semibold text-purple-900 mb-4">
            รายละเอียดกิจกรรม
        </h2>

        <p class="text-gray-800 text-lg leading-relaxed mb-8">
            <?= nl2br(htmlspecialchars($event['detail'])) ?>
        </p>

        <div class="border-t border-white/70 pt-6 flex justify-between text-base font-medium">

            <div>
                <div class="text-gray-700 mb-1">วันที่จัดกิจกรรม</div>
                <div class="text-lg">
                    <?= date("d / m / Y", strtotime($event['start'])) ?>
                </div>
            </div>

            <div>
                <div class="text-gray-700 mb-1">จำนวนที่รับ</div>
                <div class="text-lg">
                    <?= $event['max_participants'] ?>
                </div>
            </div>

            <div>
                <div class="text-gray-700 mb-1">เข้าร่วมแล้ว</div>
                <div class="text-lg">
                    <?= $currentCount ?>
                </div>
            </div>

        </div>

        <?php if ($joinStatus === 'approved' && !empty($approvedUsers)): ?>
            <div class="mt-8">
                <h3 class="font-semibold mb-3 text-lg">รายชื่อผู้เข้าร่วม</h3>
                <?php foreach ($approvedUsers as $user): ?>
                    <div class="bg-white/90 px-4 py-2 rounded-xl shadow mb-2 text-sm">
                        <?= htmlspecialchars($user['user_name']) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- ขวา -->
    <div class="flex flex-col items-center justify-center pl-10 text-center">

        <div class="mb-6">
            <div class="text-gray-700 mb-2 text-lg">สถานะ</div>

            <?php if ($joinStatus === 'approved'): ?>
                <div class="text-green-700 font-semibold text-xl">ได้รับอนุมัติ</div>
            <?php elseif ($joinStatus === 'pending'): ?>
                <div class="text-yellow-700 font-semibold text-xl">รออนุมัติ</div>
            <?php elseif ($joinStatus === 'rejected'): ?>
                <div class="text-red-700 font-semibold text-xl">ถูกปฏิเสธ</div>
            <?php elseif ($isClosed): ?>
                <div class="text-gray-600 font-semibold text-xl">ปิดรับสมัคร</div>
            <?php elseif ($isFull): ?>
                <div class="text-red-600 font-semibold text-xl">เต็มแล้ว</div>
            <?php else: ?>
                <div class="text-green-600 font-semibold text-xl">เปิดรับสมัคร</div>
            <?php endif; ?>
        </div>

        <?php if ($user_id > 0 && !$joinStatus && !$isClosed && !$isFull): ?>
            <form method="POST" action="/event-join">
                <input type="hidden" name="event_id" value="<?= $event['event_id'] ?>">
                <button
                    class="bg-purple-500 text-white px-8 py-3 rounded-full shadow-lg hover:bg-purple-600 transition duration-300 text-lg">
                    ขอเข้าร่วม
                </button>
            </form>
        <?php endif; ?>

    </div>
</div>

<div class="text-center mt-16 text-gray-400">
    ©2026
</div>

</main>
</div>

<script>
function nextSlide() {
    let slides = document.getElementsByClassName('slide');
    let current = 0;
    for (let i = 0; i < slides.length; i++) {
        if (slides[i].style.display !== "none") {
            slides[i].style.display = "none";
            current = i;
            break;
        }
    }
    slides[(current + 1) % slides.length].style.display = "block";
}

function prevSlide() {
    let slides = document.getElementsByClassName('slide');
    let current = 0;
    for (let i = 0; i < slides.length; i++) {
        if (slides[i].style.display !== "none") {
            slides[i].style.display = "none";
            current = i;
            break;
        }
    }
    slides[(current - 1 + slides.length) % slides.length].style.display = "block";
}
</script>

</body>
</html>