<?php

$conn = getConnection();

$event_id   = isset($_POST['event_id']) ? (int) $_POST['event_id'] : 0;
$title      = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';

if ($event_id <= 0) {
    die("ข้อมูลไม่ถูกต้อง");
}

$title = mysqli_real_escape_string($conn, $title);
$description = mysqli_real_escape_string($conn, $description);

mysqli_query($conn,
    "UPDATE events 
     SET event_name='$title',
         detail='$description'
     WHERE event_id=$event_id"
);

// ===== เพิ่มรูปใหม่ =====
if (!empty($_FILES['images']['name'][0])) {

    for ($i = 0; $i < count($_FILES['images']['name']); $i++) {

        if ($_FILES['images']['error'][$i] === 0) {

            $imageName = time() . '_' . basename($_FILES['images']['name'][$i]);
            $tmpName   = $_FILES['images']['tmp_name'][$i];

            // 🔥 แก้ตรงนี้ให้ตรงกับโครงสร้างคุณ
            $uploadPath = __DIR__ . '/../public/uploads/' . $imageName;

            if (move_uploaded_file($tmpName, $uploadPath)) {

                mysqli_query($conn,
                    "INSERT INTO images (event_id, path)
                     VALUES ($event_id, '$imageName')"
                );
            }
        }
    }
}

header("Location: /account");
exit;