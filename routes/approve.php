<?php
require_once DATABASES_DIR . '/event.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
    exit;
}

$regId = $_POST['reg_id'] ?? null;

if ($regId) {
    approveRegistration($regId);
}

header("Location: /event-requests");
exit;