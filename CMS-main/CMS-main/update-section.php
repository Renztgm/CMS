<?php
require 'db.php'; // Make sure $conn is initialized with mysqli_connect

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']); // Section ID
    $page_id = intval($_POST['page_id']);

    // Sanitize inputs
    // $name = trim($_POST['name'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $parent_id = isset($_POST['parent_id']) ? intval($_POST['parent_id']) : null;
    $width = trim($_POST['width'] ?? '');
    $height = trim($_POST['height'] ?? '');
    $align_items = trim($_POST['align_items'] ?? '');
    $justify_content = trim($_POST['justify_content'] ?? '');
    $padding = trim($_POST['padding'] ?? '');
    $margin = trim($_POST['margin'] ?? '');
    $background_color = trim($_POST['background_color'] ?? '');
    $border_value = trim($_POST['border_value'] ?? '');
    $border_style = trim($_POST['border_style'] ?? '');
    $border_color = trim($_POST['border_color'] ?? '');
    $border_radius = trim($_POST['border_radius'] ?? '');
    $flex_direction = trim($_POST['flex_direction'] ?? '');

    // SQL Query
    $sql = "
        UPDATE sections SET
            type = ?,
            parent_id = ?,
            width = ?,
            height = ?,
            align_items = ?,
            justify_content = ?,
            padding = ?,
            margin = ?,
            background_color = ?,
            border_value = ?,
            border_style = ?,
            border_color = ?,
            border_radius = ?,
            flex_direction = ?
        WHERE id = ?
    ";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt,
            'ssisssssssssssi',
            // $name,
            $type,
            $parent_id,
            $width,
            $height,
            $align_items,
            $justify_content,
            $padding,
            $margin,
            $background_color,
            $border_value,
            $border_style,
            $border_color,
            $border_radius,
            $flex_direction,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            // Redirect back with page ID
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
