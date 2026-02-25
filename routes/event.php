<?php 
require_once DATABASES_DIR . '/event.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
    exit;
}

$eventId = $_GET['id'] ?? null;

if (!$eventId) {
    header("Location: /home");
    exit;
}

$event = getEventById($eventId);

if (!$event) {
    header("Location: /home");
    exit;
}

/* ===== จำนวนคนทั้งหมด (เดิมของคุณ) ===== */
$currentCount = countParticipants($eventId);

/* ===== เช็คสถานะผู้ใช้ปัจจุบัน (เดิมของคุณ) ===== */
$joinData = checkUserJoined($eventId, $_SESSION['user_id']);
$joinStatus = $joinData['reg_status'] ?? null;

/* ===== เพิ่ม: ดึงเฉพาะคนที่ approved แล้ว ===== */
$approvedParticipants = getApprovedParticipants($eventId);

renderView('event-detail', [
    'event' => $event,
    'currentCount' => $currentCount,
    'joinStatus' => $joinStatus,
    'approvedParticipants' => $approvedParticipants
]);