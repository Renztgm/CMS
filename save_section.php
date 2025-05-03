<?php
include("db.php"); // Include your database connection file

$page_id = $_POST['page_id'] ?? null; // Use POST instead of GET for page_id
if (!$page_id) {
    die("Error: Page ID is required.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['page_id'], $_POST['section-name'], $_POST['section-type'], $_POST['section-width'], $_POST['section-height'], 
              $_POST['align-items'], $_POST['justify-content'], $_POST['padding-value'], $_POST['margin-value'],
              $_POST['border-value'], $_POST['border-style'], $_POST['border-color'], $_POST['border-radius-value'], 
              $_POST['flex-direction'])) {

        // Required fields
        $page_id = $_POST['page_id'];
        $name = $_POST['section-name'];
        $type = $_POST['section-type'];
        $parent_id = null; // Set to null unless you're using it from the form
        $width = $_POST['section-width'] . ($_POST['width-unit'] ?? '');
        $height = $_POST['section-height'] . ($_POST['height-unit'] ?? '');
        $align_items = $_POST['align-items'];
        $justify_content = $_POST['justify-content'];
        $padding_value = $_POST['padding-value'] . ($_POST['padding-unit'] ?? '');
        $margin_value = $_POST['margin-value'] . ($_POST['margin-unit'] ?? '');
        $background_color = $_POST['section-bg'] ?? '#ffffff';
        $border_value = $_POST['border-value'] . ($_POST['border-unit'] ?? '');
        $border_style = $_POST['border-style'];
        $border_color = $_POST['border-color'];
        $border_radius_value = $_POST['border-radius-value'] . ($_POST['border-radius-unit'] ?? '');
        $flex_direction = $_POST['flex-direction'];
        $font_family = $_POST['font-family'] ?? 'Arial, sans-serif'; // default value
        $font_size = ($_POST['font-size'] ?? '16') . ($_POST['font-size-unit'] ?? 'px');
        $created_at = date("Y-m-d H:i:s");

        // Prepare insert statement
        $stmt = $conn->prepare("INSERT INTO sections 
            (name, type, parent_id, width, height, align_items, justify_content, padding, margin, background_color, 
             border_value, border_style, border_color, border_radius, created_at, page_id, flex_direction, font_family, font_size)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("sssssssssssssssssss", 
                          $name, $type, $parent_id, $width, $height, $align_items, $justify_content, 
                          $padding_value, $margin_value, $background_color, $border_value, $border_style, 
                          $border_color, $border_radius_value, $created_at, $page_id, $flex_direction, 
                          $font_family, $font_size);

        if ($stmt->execute()) {
            header("Location: edited-page.php?id=" . $page_id);
            exit;
        } else {
            echo "Database error: " . $stmt->error;
        }

    } else {
        echo "Error: Missing required fields.";
    }
}else {
    // Handle invalid request method
    echo "Invalid request method.";
}
?>