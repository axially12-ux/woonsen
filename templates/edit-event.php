<?php
$conn = getConnection();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    echo "ไม่พบกิจกรรม";
    exit;
}

$event = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM events WHERE event_id = $id LIMIT 1")
);

if (!$event) {
    echo "ไม่พบข้อมูลกิจกรรม";
    exit;
}

$images = mysqli_query(
    $conn,
    "SELECT * FROM images WHERE event_id = $id"
);
?>

<!-- ต้องมี Tailwind ถ้ายังไม่มีใน layout -->
<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-gray-100 py-10 px-4">

    <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-2xl p-8">

        <h2 class="text-3xl font-bold text-gray-800 mb-6">
            ✏️ แก้ไขกิจกรรม
        </h2>

        <form action="/update-event"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6">

            <input type="hidden" name="event_id" value="<?= $id ?>">

            <!-- ชื่อกิจกรรม -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">
                    ชื่อกิจกรรม
                </label>
                <input type="text"
                       name="title"
                       value="<?= htmlspecialchars($event['event_name']) ?>"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                       required>
            </div>

            <!-- รายละเอียด -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">
                    รายละเอียด
                </label>
                <textarea name="description"
                          rows="4"
                          class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                          required><?= htmlspecialchars($event['detail']) ?></textarea>
            </div>

            <!-- รูปภาพปัจจุบัน -->
            <div>
                <h3 class="text-xl font-semibold text-gray-700 mb-4">
                    📷 รูปภาพปัจจุบัน
                </h3>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <?php while ($img = mysqli_fetch_assoc($images)) { ?>
                        <div class="relative group">
                            <img src="/uploads/<?= htmlspecialchars($img['path']) ?>"
                                 class="rounded-xl shadow-md w-full h-40 object-cover">

                            <a href="/delete-image?id=<?= $img['image_id'] ?>"
                               class="absolute top-2 right-2 bg-red-500 text-white text-sm px-2 py-1 rounded-lg opacity-0 group-hover:opacity-100 transition">
                               ลบ
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <!-- เพิ่มรูป -->
            <div>
                <label class="block text-gray-700 font-semibold mb-2">
                    เพิ่มรูปใหม่
                </label>
                <input type="file"
                       name="images[]"
                       multiple
                       class="w-full border border-gray-300 rounded-xl px-4 py-2 bg-white">
            </div>

            <!-- ปุ่ม -->
            <div class="flex justify-between items-center pt-4">

                <a href="/account"
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-xl transition">
                   ยกเลิก
                </a>

                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-2 rounded-xl shadow-md transition">
                    💾 บันทึกการแก้ไข
                </button>

            </div>

        </form>

    </div>

</div>