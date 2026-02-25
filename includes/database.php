<?php

$hostname = 'localhost';
$dbName   = 'project';
$username = 'pro1';
$password = '1234';

// ======================
// เชื่อมต่อฐานข้อมูล
// ======================
$conn = new mysqli($hostname, $username, $password, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

// ======================
// ฟังก์ชันดึง connection
// ======================
function getConnection(): mysqli
{
    global $conn;
    return $conn;
}

// ======================
// database functions อื่น ๆ
// ======================

