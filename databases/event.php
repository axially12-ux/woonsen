<?php

/* ==============================
   สร้างกิจกรรม
============================== */
function insertEvent($event)
{
    global $conn;

    $stmt = $conn->prepare("INSERT INTO events
        (event_name, detail, max_participants, start, end, event_status, created_by)
        VALUES (?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "ssisssi",
        $event['event_name'],
        $event['detail'],
        $event['max_participants'],
        $event['start'],
        $event['end'],
        $event['event_status'],
        $event['created_by']
    );

    if ($stmt->execute()) {
        return $stmt->insert_id;
    }

    return false;
}

/* ==============================
   ดึงกิจกรรมทั้งหมด
============================== */
function getEvents($search = '', $date = '', $status = 'active')
{
    global $conn;

    $sql = "
        SELECT e.*, i.path
        FROM events e
        LEFT JOIN images i ON e.event_id = i.event_id
        WHERE 1
    ";

    if (!empty($search)) {
        $search = $conn->real_escape_string($search);
        $sql .= " AND e.event_name LIKE '%$search%'";
    }

    if (!empty($date)) {
        $date = $conn->real_escape_string($date);
        $sql .= " AND DATE(e.start) = '$date'";
    }

    if ($status === 'active') {
        $sql .= " AND e.event_status = 'open'";
    } elseif ($status === 'closed') {
        $sql .= " AND e.event_status = 'closed'";
    }

    $sql .= " GROUP BY e.event_id ORDER BY e.event_id DESC";

    $result = $conn->query($sql);

    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    return $events;
}

/* ==============================
   เพิ่มรูปภาพ
============================== */
function insertImage($event_id, $path)
{
    global $conn;

    $stmt = $conn->prepare("INSERT INTO images (event_id, path) VALUES (?, ?)");
    $stmt->bind_param("is", $event_id, $path);

    return $stmt->execute();
}

/* ==============================
   ดึงกิจกรรมตาม ID
============================== */
function getEventById($id)
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

/* ==============================
   นับผู้เข้าร่วม (เฉพาะ approved)
============================== */
function countParticipants($eventId)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT COUNT(*) as total
        FROM registrations
        WHERE event_id = ?
        AND reg_status = 'approved'
    ");

    $stmt->bind_param("i", $eventId);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc()['total'];
}

/* ==============================
   เช็คว่าผู้ใช้สมัครแล้วหรือยัง
============================== */
function checkUserJoined($eventId, $userId)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT reg_status
        FROM registrations
        WHERE event_id = ?
        AND user_id = ?
    ");

    $stmt->bind_param("ii", $eventId, $userId);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

/* ==============================
   สมัครกิจกรรม (pending)
============================== */
function joinEvent($eventId, $userId)
{
    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO registrations
        (user_id, event_id, registration_date, reg_status)
        VALUES (?, ?, NOW(), 'pending')
    ");

    $stmt->bind_param("ii", $userId, $eventId);

    return $stmt->execute();
}

/* ==============================
   อนุมัติ
============================== */
function approveRegistration($regId)
{
    global $conn;

    $stmt = $conn->prepare("
        UPDATE registrations
        SET reg_status = 'approved'
        WHERE reg_id = ?
    ");

    $stmt->bind_param("i", $regId);

    return $stmt->execute();
}

/* ==============================
   ปฏิเสธ
============================== */
function rejectRegistration($regId)
{
    global $conn;

    $stmt = $conn->prepare("
        UPDATE registrations
        SET reg_status = 'rejected'
        WHERE reg_id = ?
    ");

    $stmt->bind_param("i", $regId);

    return $stmt->execute();
}

/* ==============================
   กิจกรรมที่สร้างโดยผู้ใช้
============================== */
function getEventsByUser($userId)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT e.*, i.path
        FROM events e
        LEFT JOIN images i ON e.event_id = i.event_id
        WHERE e.created_by = ?
        GROUP BY e.event_id
        ORDER BY e.event_id DESC
    ");

    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();

    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    return $events;
}

/* ==============================
   กิจกรรมที่ผู้ใช้เข้าร่วม
============================== */
function getJoinedEvents($userId)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT e.*, r.reg_status, i.path AS image
        FROM registrations r
        JOIN events e ON r.event_id = e.event_id
        LEFT JOIN images i ON e.event_id = i.event_id
        WHERE r.user_id = ?
        GROUP BY e.event_id
        ORDER BY r.registration_date DESC
    ");

    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();

    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }

    return $events;
}

/* ==============================
   ลบกิจกรรม
============================== */
function deleteEvent($eventId)
{
    global $conn;

    $stmt = $conn->prepare("DELETE FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $eventId);

    return $stmt->execute();
}

/* ==============================
   แก้ไขกิจกรรม
============================== */
function updateEvent($event)
{
    global $conn;

    $stmt = $conn->prepare("
        UPDATE events
        SET event_name = ?, detail = ?, max_participants = ?, start = ?, end = ?, event_status = ?
        WHERE event_id = ?
    ");

    $stmt->bind_param(
        "ssisssi",
        $event['event_name'],
        $event['detail'],
        $event['max_participants'],
        $event['start'],
        $event['end'],
        $event['event_status'],
        $event['event_id']
    );

    return $stmt->execute();
}

/* ==============================
   คำขอเข้าร่วมกิจกรรม (รออนุมัติ)
   สำหรับเจ้าของกิจกรรม
============================== */
function getPendingRequestsByOwner($ownerId)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT r.reg_id, r.registration_date,
               u.username,
               e.event_name, e.event_id
        FROM registrations r
        JOIN events e ON r.event_id = e.event_id
        JOIN users u ON r.user_id = u.user_id
        WHERE e.created_by = ?
        AND r.reg_status = 'pending'
        ORDER BY r.registration_date DESC
    ");

    $stmt->bind_param("i", $ownerId);
    $stmt->execute();

    $result = $stmt->get_result();

    $requests = [];
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }

    return $requests;
}

/* ==============================
   ต้องอนุมัติ (approved) แล้วเท่านั้น
    ถึงจะแสดงชื่อคนเข้าร่วม
============================== */

function getApprovedParticipants($eventId) {
    $conn = getConnection();
    $eventId = (int)$eventId;

    $approvedUsers = [];

    $query = "
        SELECT u.user_name
        FROM registrations r
        JOIN users u ON r.user_id = u.user_id
        WHERE r.event_id = $eventId
        AND r.reg_status = 'approved'
    ";

    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $approvedUsers[] = $row;
        }
    }

    return $approvedUsers;
}