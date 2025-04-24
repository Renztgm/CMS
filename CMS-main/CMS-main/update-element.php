<?php
include('db.php'); // Include your database connection file

$page_id = $_POST['page_id'] ?? null;

try {
    $pdo = new PDO('mysql:host=localhost;dbname=z', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Retrieve form data
        $id = $_POST['id'];
        $section_id = $_POST['section_id'];
        $element_type = $_POST['element_type'];
        $content = $_POST['content'];
        $width = $_POST['width'];
        $height = $_POST['height'];
        $alignment = $_POST['alignment'];
        $padding = $_POST['padding'];
        $margin = $_POST['margin'];
        $border = $_POST['border'];
        $border_radius = $_POST['border_radius'];
        $font_family = $_POST['font-family'];
        $font_size = $_POST['font-size'] . $_POST['font-size-unit'];

        // Debugging: Print the form data
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";

        // Prepare the SQL query to update the element
        $stmt = $pdo->prepare("
            UPDATE section_elements 
            SET 
                element_type = :element_type,
                content = :content,
                width = :width,
                height = :height,
                alignment = :alignment,
                padding = :padding,
                margin = :margin,
                border = :border,
                border_radius = :border_radius,
                font_family = :font_family,
                font_size = :font_size
            WHERE id = :id
        ");

        // Debugging: Check if the query executes successfully
        if ($stmt->execute([
            ':element_type' => $element_type,
            ':content' => $content,
            ':width' => $width,
            ':height' => $height,
            ':alignment' => $alignment,
            ':padding' => $padding,
            ':margin' => $margin,
            ':border' => $border,
            ':border_radius' => $border_radius,
            ':font_family' => $font_family,
            ':font_size' => $font_size,
            ':id' => $id
        ])) {
            echo "Update successful!";
        } else {
            echo "Update failed!";
            print_r($stmt->errorInfo());
        }

        // Redirect back to the edited page
        echo "<script>alert('Element updated successfully.'); window.location.href = 'edited-page.php?id=$page_id';</script>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
