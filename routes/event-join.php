<?php
require_once DATABASES_DIR . '/event.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
    exit;
}

$eventId = $_POST['event_id'] ?? null;
$userId  = $_SESSION['user_id'];

if (!$eventId) {
    header("Location: /home");
    exit;
}

$event = getEventById($eventId);

if (!$event) {
    header("Location: /home");
    exit;
}

$currentCount = countParticipants($eventId);

/* =========================
   1. เช็คว่างานจบหรือยัง
========================= */
if (strtotime($event['end']) < strtotime(date('Y-m-d'))) {
    header("Location: /event?id=$eventId&error=closed");
    exit;
}

/* =========================
   2. เช็คว่างานเต็มหรือยัง
========================= */
if ($event['max_participants'] != 0 &&
    $currentCount >= $event['max_participants']) {

    header("Location: /event?id=$eventId&error=full");
    exit;
}

/* =========================
   3. เช็คว่าเคยสมัครแล้วหรือยัง
========================= */
$joined = checkUserJoined($eventId, $userId);

if ($joined) {
    header("Location: /event?id=$eventId&error=duplicate");
    exit;
}

/* =========================
   4. สมัครได้
========================= */
joinEvent($eventId, $userId);

header("Location: /event?id=$eventId&success=1");
exit;