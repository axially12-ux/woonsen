<?php

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        get();
        break;
    // กรณีอื่นๆ เช่น POST สามารถเพิ่มได้ที่นี่
    case 'POST':
        post();
        break;
}

function get(): void{
    // ประมวลผลก่อนแสดงผลหน้า
    renderView('contact', ['title' => 'Contact Us']);
}

function post(): void{
    // ประมวลผลคำขอแบบ POST ที่นี่ (ถ้ามี)
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';
    // เช็คข้อมูลว่างแล้วเตือน
    if (empty($name) || empty($email) || empty($message)) {
        $message = "กรุณากรอกข้อมูลให้ครบถ้วน";
        echo "<script type='text/javascript'>alert('$message');window.history.back();</script>";
        return;
    } 
    // mask email
    $email = preg_replace('/(?<=.).(?=[^@]*?.@)/', '*', $email);
    // แสดงหน้าขอบคุณหลังส่งข้อความ
    renderView('thank', ['name' => $name, 'email' => $email, 'message' => $message]);

}