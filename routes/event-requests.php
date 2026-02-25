<?php
require_once DATABASES_DIR . '/event.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
    exit;
}

global $conn;

$ownerId = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT r.reg_id,
           u.user_name AS username,
           e.event_name,
           r.registration_date
    FROM registrations r
    JOIN events e ON r.event_id = e.event_id
    JOIN users u ON r.user_id = u.user_id
    WHERE e.created_by = ?
    AND r.reg_status = 'pending'
");

$stmt->bind_param("i", $ownerId);
$stmt->execute();

$result = $stmt->get_result();
$requests = $result->fetch_all(MYSQLI_ASSOC);

/* 👇 สำคัญมาก */
require TEMPLATES_DIR . '/event-requests.php';