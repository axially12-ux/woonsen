<?php
require_once DATABASES_DIR . '/users.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    renderview('register');
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_name = trim($_POST['user_name']);
    $tel = trim($_POST['tel']);
    $email = trim($_POST['email']);
    $gender = $_POST['gender'];
    $birthday = $_POST['birthdate'];
    $county = trim($_POST['county']);
    $password = trim($_POST['password']);
    $job = trim($_POST['job']);

    // ✅ เช็คกรอกครบ
    if (empty($user_name) || empty($email) || empty($password)) {
        renderview('register', ['error' => 'กรุณากรอกข้อมูลให้ครบ']);
        return;
    }

    // ✅ เช็ค username / email ซ้ำ
    $existingUser = findUserByUsernameOrEmail($user_name, $email);

    if ($existingUser) {

        if ($existingUser['user_name'] === $user_name) {
            $error = "ชื่อผู้ใช้นี้ถูกใช้ไปแล้ว";
        } else {
            $error = "อีเมลนี้ถูกใช้ไปแล้ว";
        }

        renderview('register', ['error' => $error]);
        return;
    }

    $user = [
        'user_name' => $user_name,
        'tel' => $tel,
        'email' => $email,
        'gender' => $gender,
        'birthday' => $birthday,
        'county' => $county,
        'password' => $password,
        'job' => $job
    ];

    if (insertUser($user)) {
        header("Location: /success");
        exit;
    } else {
        renderview('register', ['error' => 'สมัครสมาชิกไม่สำเร็จ']);
    }
}