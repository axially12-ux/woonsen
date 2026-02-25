<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ลบข้อมูล session ทั้งหมด
$_SESSION = [];

// ทำลาย session
session_destroy();

// ลบ cookie session (กันค้าง)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Redirect ไปหน้า login
header("Location: /login");
exit;