<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สร้างกิจกรรม</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-screen w-screen overflow-hidden bg-gray-200">

    <div class="flex h-full w-full">

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
        <div class="flex-1 bg-gray-100 p-10 overflow-y-auto">

            <!-- Banner -->
            <div class="relative h-40 rounded-3xl overflow-hidden mb-8 shadow-lg">

                <!-- รูปพื้นหลังจากออนไลน์ -->
                <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=1920&auto=format&fit=crop"
                    class="absolute inset-0 w-full h-full object-cover">

                <!-- เลเยอร์มืดทับ -->
                <div class="absolute inset-0 bg-black/50"></div>

                <!-- ข้อความตรงกลาง -->
                <h2 class="absolute inset-0 flex items-center justify-center
               text-4xl font-bold text-white drop-shadow-lg">
                    สร้างกิจกรรม
                </h2>

            </div>

            <!-- FORM -->
            <form method="POST" enctype="multipart/form-data" class="space-y-6">

                <!-- 🔥 เพิ่ม hidden status -->
                <input type="hidden" name="event_status" value="open">

                <!-- ชื่อกิจกรรม -->
                <div>
                    <label class="text-purple-600 font-semibold">ชื่อกิจกรรม</label>
                    <input type="text" name="event_name"
                        class="w-full mt-2 p-4 rounded-xl bg-gray-200 outline-none" required>
                </div>

                <!-- รายละเอียด + จำนวน -->
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="text-purple-600 font-semibold">รายละเอียดกิจกรรม</label>
                        <textarea name="detail" rows="5"
                            class="w-full mt-2 p-4 rounded-xl bg-gray-200 outline-none" required></textarea>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="text-purple-600 font-semibold">จำนวนที่รับ</label>
                            <input type="number" name="max_participants"
                                class="w-full mt-2 p-4 rounded-xl bg-gray-200 outline-none" required>
                        </div>

                        <div>
                            <label class="text-purple-600 font-semibold">วันที่จัดกิจกรรม</label>
                            <div class="flex gap-4 mt-2">
                                <input type="datetime-local" name="start"
                                    class="flex-1 p-3 rounded-full bg-blue-200 outline-none" required>
                                <input type="datetime-local" name="end"
                                    class="flex-1 p-3 rounded-full bg-blue-200 outline-none" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- เพิ่มรูปภาพ -->
                <div>
                    <label class="text-purple-600 font-semibold">เพิ่มรูปภาพ</label>

                    <div class="grid grid-cols-5 gap-4 mt-3">

                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <div onclick="selectImage(<?= $i ?>)"
                                class="relative h-28 bg-gray-300 rounded-xl cursor-pointer overflow-hidden flex items-center justify-center">

                                <img id="preview<?= $i ?>" class="hidden w-full h-full object-cover">
                                <span id="placeholder<?= $i ?>" class="text-gray-500 text-2xl">+</span>

                                <button type="button"
                                    id="removeBtn<?= $i ?>"
                                    onclick="removeImage(event,<?= $i ?>)"
                                    class="hidden absolute top-2 right-2 bg-black/60 text-white text-xs px-2 py-1 rounded-full">
                                    ✕
                                </button>

                                <!-- 🔥 สำคัญ: ใช้ name="images[]" ให้ตรง route -->
                                <input type="file"
                                    id="file<?= $i ?>"
                                    name="images[]"
                                    class="hidden"
                                    accept="image/*"
                                    onchange="previewImage(event,<?= $i ?>)">
                            </div>
                        <?php endfor; ?>

                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-center gap-6 pt-6">

                    <button type="button"
                        onclick="window.location.href='/home'"
                        class="px-8 py-3 bg-blue-400 text-white rounded-full hover:bg-blue-500 transition">
                        ← กลับ
                    </button>

                    <button type="reset"
                        class="px-8 py-3 bg-gray-400 text-white rounded-full hover:bg-gray-500 transition">
                        ล้าง
                    </button>

                    <button type="submit"
                        class="px-8 py-3 bg-pink-500 text-white rounded-full hover:bg-pink-600 transition">
                        บันทึก
                    </button>

                </div>

                <p class="text-center text-gray-500 pt-4">©2026</p>

            </form>

        </div>
    </div>

    <script>
        function selectImage(number) {
            document.getElementById("file" + number).click();
        }

        function previewImage(event, number) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = document.getElementById("preview" + number);
                    const placeholder = document.getElementById("placeholder" + number);
                    const removeBtn = document.getElementById("removeBtn" + number);

                    img.src = e.target.result;
                    img.classList.remove("hidden");
                    placeholder.classList.add("hidden");
                    removeBtn.classList.remove("hidden");
                }

                reader.readAsDataURL(file);
            }
        }

        function removeImage(event, number) {
            event.stopPropagation();

            const img = document.getElementById("preview" + number);
            const placeholder = document.getElementById("placeholder" + number);
            const fileInput = document.getElementById("file" + number);
            const removeBtn = document.getElementById("removeBtn" + number);

            img.src = "";
            img.classList.add("hidden");
            placeholder.classList.remove("hidden");
            removeBtn.classList.add("hidden");
            fileInput.value = "";
        }
    </script>

</body>

</html>