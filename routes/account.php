<?php
require_once DATABASES_DIR . '/event.php';
require_once DATABASES_DIR . '/users.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
    exit;
}

$userId = $_SESSION['user_id'];

$user = getUserById($userId);
$myEvents = getEventsByUser($userId);
$joinedEvents = getJoinedEvents($userId);

renderView('account', [
    'user' => $user,
    'myEvents' => $myEvents,
    'joinedEvents' => $joinedEvents
]);