<?php
require 'db.php'; // this should define $conn = mysqli_connect(...)

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $section_id = intval($_POST['section_id']);

    $page_id = $_POST['page_id'] ?? null;
    $element_type = trim($_POST['element_type'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $width = trim($_POST['width'] ?? '');
    $height = trim($_POST['height'] ?? '');
    $alignment = trim($_POST['alignment'] ?? '');
    $padding = trim($_POST['padding'] ?? '');
    $margin = trim($_POST['margin'] ?? '');
    $border = trim($_POST['border'] ?? '');
    $border_radius = trim($_POST['border_radius'] ?? '');

    // Optional: check if section_id exists to avoid FK error
    $checkSection = mysqli_query($conn, "SELECT id FROM sections WHERE id = $section_id LIMIT 1");
    if (mysqli_num_rows($checkSection) === 0) {
        die("Error: section_id $section_id does not exist in sections table.");
    }

    $sql = "
        UPDATE section_elements SET
            section_id = ?,
            element_type = ?,
            content = ?,
            width = ?,
            height = ?,
            alignment = ?,
            padding = ?,
            margin = ?,
            border = ?,
            border_radius = ?
        WHERE id = ?
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt,
            'isssssssssi', // type specifiers: i = int, s = string
            $section_id,
            $element_type,
            $content,
            $width,
            $height,
            $alignment,
            $padding,
            $margin,
            $border,
            $border_radius,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: edited-page.php?id=$page_id");
            exit();
        } else {
            echo "Execute failed: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Prepare failed: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "Invalid request method.";
}
