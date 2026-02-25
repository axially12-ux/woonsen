<?php

$conn = getConnection();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("ไม่พบกิจกรรม");
}

/* ลบรูปภาพออกจากโฟลเดอร์ก่อน */
$result = mysqli_query($conn, "SELECT path FROM images WHERE event_id = $id");

while ($row = mysqli_fetch_assoc($result)) {
    $filePath = __DIR__ . '/../public/uploads/' . $row['path'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

/* ลบข้อมูลในตาราง images */
mysqli_query($conn, "DELETE FROM images WHERE event_id = $id");

/* ลบข้อมูลการเข้าร่วม */
mysqli_query($conn, "DELETE FROM registrations WHERE event_id = $id");

/* ลบกิจกรรม */
mysqli_query($conn, "DELETE FROM events WHERE event_id = $id");

header("Location: /account");
exit;