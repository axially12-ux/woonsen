<?php
require_once DATABASES_DIR . '/users.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    renderView('login');
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_name = trim($_POST['user_name']);
    $password = trim($_POST['password']);

    if (empty($user_name) || empty($password)) {
        renderView('login', ['error' => 'กรุณากรอกข้อมูลให้ครบ']);
        return;
    }

    $user = findUserByUsername($user_name);

    // ❌ ไม่เจอ username
    if (!$user) {
        renderView('login', ['error' => 'ชื่อผู้ใช้ไม่ถูกต้อง']);
        return;
    }

    // ❌ รหัสผ่านไม่ตรง
    if (!password_verify($password, $user['password'])) {
        renderView('login', ['error' => 'รหัสผ่านไม่ถูกต้อง']);
        return;
    }

    // ✅ Login สำเร็จ
    session_start();
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_name'] = $user['user_name'];

    header("Location: /");
    exit;
}