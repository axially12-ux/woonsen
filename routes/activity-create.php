<?php
require_once DATABASES_DIR . '/event.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    renderView('activity-create');
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $event = [
        'event_name' => $_POST['event_name'],
        'detail' => $_POST['detail'],
        'max_participants' => (int)$_POST['max_participants'],
        'start' => $_POST['start'],
        'end' => $_POST['end'],
        'event_status' => $_POST['event_status'],
        'created_by' => $_SESSION['user_id']
    ];

    $event_id = insertEvent($event);

    if ($event_id) {

        // ===== อัปโหลดรูป =====
       $upload_dir = __DIR__ . '/../public/uploads/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (!empty($_FILES['images']['name'][0])) {

            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {

                if ($_FILES['images']['error'][$key] === 0) {

                    $file_name = time() . "_" . basename($_FILES['images']['name'][$key]);
                    $target = $upload_dir . $file_name;

                    if (move_uploaded_file($tmp_name, $target)) {
                        insertImage($event_id, $file_name);
                    }
                }
            }
        }

        header("Location: /home");
        exit;

    } else {
        renderView('activity-create', ['error' => 'สร้างกิจกรรมไม่สำเร็จ']);
    }
}