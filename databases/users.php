<?php

// ======================
// เพิ่มผู้ใช้
// ======================
function insertUser($user): bool
{
    global $conn;

    $sql = "INSERT INTO users
    (user_name, tel, email, gender, birthday, county, password, job)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    // hash password ตรงนี้ที่เดียวพอ
    $hashPassword = password_hash($user['password'], PASSWORD_DEFAULT);

    $stmt->bind_param(
        "ssssssss",
        $user['user_name'],
        $user['tel'],
        $user['email'],
        $user['gender'],
        $user['birthday'],   // ต้องใช้ birthday ให้ตรงกับ DB
        $user['county'],
        $hashPassword,
        $user['job']
    );

    $stmt->execute();

    return $stmt->affected_rows > 0;
}


// ======================
// เช็ค username หรือ email ซ้ำ
// ======================
function findUserByUsernameOrEmail($username, $email)
{
    global $conn;

    $sql = "SELECT * FROM users WHERE user_name = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function findUserByUsername($username)
{
    global $conn;

    $sql = "SELECT * FROM users WHERE user_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function findUserById($id)
{
    global $conn;

    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getUserById($id)
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}