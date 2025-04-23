<?php
include("db.php"); // Include your database connection file

$page_id = $_GET['id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure the necessary fields are present in the POST request
    if (isset($_POST['page_id'], $_POST['section-name'], $_POST['section-type'], $_POST['section-width'], $_POST['section-height'], 
              $_POST['align-items'], $_POST['justify-content'], $_POST['padding-value'], $_POST['margin-value'],
              $_POST['border-value'], $_POST['border-style'], $_POST['border-color'], $_POST['border-radius-value'], $_POST['flex-direction'])) {

        // Retrieve form data
        $page_id = $_POST['page_id'];
        $name = $_POST['section-name'];
        $type = $_POST['section-type'];
        $parent_id = $_POST['section-type'];
        $width = $_POST['section-width'] . $_POST['width-unit'];
        $height = $_POST['section-height'] . $_POST['height-unit'];
        $align_items = $_POST['align-items'];
        $justify_content = $_POST['justify-content'];
        $padding_value = $_POST['padding-value'] . $_POST['padding-unit'];
        $padding_unit = $_POST['padding-unit'];
        $margin_value = $_POST['margin-value'] . $_POST['margin-unit'];
        $margin_unit = $_POST['margin-unit'];
        $background_color = $_POST['section-bg'];
        $border_value = $_POST['border-value'] . $_POST['border-unit'];
        $border_unit = $_POST['border-unit'];
        $border_style = $_POST['border-style'];
        $border_color = $_POST['border-color'];
        $border_radius_value = $_POST['border-radius-value'] . $_POST['border-radius-unit'];
        $flex_direction = $_POST['flex-direction'];
        $created_at = date("Y-m-d H:i:s");

        // Prepare SQL query to insert data into sections table
        $stmt = $conn->prepare("INSERT INTO sections 
                               (name, type, parent_id, width, height, align_items, justify_content, padding, margin, background_color, 
                                border_value, border_style, border_color, border_radius, created_at, page_id, flex_direction)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // Bind parameters to the SQL query
        $stmt->bind_param("sssssssssssssssss", $name, $type, $parent_id, $width, $height, $align_items, $justify_content, 
                          $padding_value, $margin_value, $background_color, $border_value, $border_style, $border_color, 
                          $border_radius_value, $created_at, $page_id, $flex_direction);

        // Execute the query
        if ($stmt->execute()) {
            echo "Section created successfully!";
            header("Location: edited-page.php?id=". $page_id);// Redirect to the blank page or please change this to the desired page
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

    } else {
        // Handle missing form data
        echo "Error: Missing required fields.";
    }
} else {
    // Handle invalid request method
    echo "Invalid request method.";
}
?>
