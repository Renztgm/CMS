<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $page_id = $_POST['page_id'];
    echo $page_id;
    // Ensure a file is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        
        $section_id = $_POST['section_id'];
        $width = $_POST['width'];
        $height = $_POST['height'];
        $image = $_FILES['image'];

        // File Upload Path
        $upload_dir = 'uploads/';
        $file_name = basename($image['name']);
        $file_path = $upload_dir . $file_name;

        // Move the uploaded file to the upload directory
        if (move_uploaded_file($image['tmp_name'], $file_path)) {
            // Insert into database
            $conn = new mysqli("localhost", "root", "", "z");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $stmt = $conn->prepare("INSERT INTO image (section_id, width, height, file_path) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $section_id, $width, $height, $file_path);

            if ($stmt->execute()) {
                echo "Image uploaded successfully!";
                header("Location: image.php?id=$page_id");
            } else {
                echo "Error uploading image: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        } else {
            echo "Failed to upload file.";
        }
    } else {
        echo "No file uploaded or there was an error.";
    }
}
?>
