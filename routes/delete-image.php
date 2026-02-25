<?php

$conn = getConnection();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header("Location: /account");
    exit;
}

$img = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM images WHERE image_id = $id LIMIT 1")
);

if ($img) {

    $filePath = __DIR__ . '/../uploads/' . $img['path'];

    if (file_exists($filePath)) {
        unlink($filePath);
    }

    mysqli_query($conn,
        "DELETE FROM images WHERE image_id = $id"
    );
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;