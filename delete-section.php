<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $page_id = $_POST['page_id'];

    $stmt = mysqli_prepare($conn, "DELETE FROM sections WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: edited-page.php?id=$page_id");
        exit();
    } else {
        echo "Failed to delete section: " . mysqli_stmt_error($stmt);
    }
}
