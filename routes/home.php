<?php
require_once DATABASES_DIR . '/event.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
    exit;
}

$search = $_GET['search'] ?? '';
$date   = $_GET['date'] ?? '';
$status = $_GET['status'] ?? 'active';

/* ดึงกิจกรรม (รวมรูปจาก LEFT JOIN แล้ว) */
$events = getEvents($search, $date, $status);

renderView('home', [
    'events' => $events,
    'search' => $search,
    'date'   => $date,
    'status' => $status
]);