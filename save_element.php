<?php
$page_id = $_POST['page_id'] ?? null;
try {
    $pdo = new PDO('mysql:host=localhost;dbname=z', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Retrieve font-family and font-size from the form
        $font_family = $_POST['font-family'] ?? 'Arial'; // Default to Arial if not provided
        $font_size = ($_POST['font-size'] ?? '16') . ($_POST['font-size-unit'] ?? 'px'); // Default to 16px if not provided

        // Prepare the SQL query to insert the element
        $stmt = $pdo->prepare("
            INSERT INTO section_elements 
            (section_id, element_type, content, width, height, alignment, padding, margin, border, border_radius, font_family, font_size, created_at)
            VALUES 
            (:section_id, :element_type, :content, :width, :height, :alignment, :padding, :margin, :border, :border_radius, :font_family, :font_size, NOW())
        ");

        // Execute the query with the provided data
        $stmt->execute([
            ':section_id'    => $_POST['section_id'],
            ':element_type'  => $_POST['element_type'],
            ':content'       => $_POST['content'],
            ':width'         => $_POST['width'],
            ':height'        => $_POST['height'],
            ':alignment'     => $_POST['alignment'],
            ':padding'       => $_POST['padding'],
            ':margin'        => $_POST['margin'],
            ':border'        => $_POST['border'],
            ':border_radius' => $_POST['border_radius'],
            ':font_family'   => $font_family,
            ':font_size'     => $font_size
        ]);

        echo "<script>alert('Element saved successfully.'); window.location.href = 'edited-page.php?id=$page_id';</script>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>