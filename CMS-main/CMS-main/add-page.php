<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form inputs
    $websiteName = $_POST['website_name'];
    $pageTitle = $_POST['filename'];
    $description = $_POST['description'];

    // Default logo path
    $logoPath = '';

    // Handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        // Define upload directory
        $uploadDir = 'uploads/';
        
        // Create the directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate a unique file name for the uploaded logo
        $logoName = uniqid() . '_' . basename($_FILES['logo']['name']);
        $targetFile = $uploadDir . $logoName;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFile)) {
            // Set the logo path for database storage
            $logoPath = $targetFile;
        } else {
            echo "Failed to upload logo.";
            exit;
        }
    } else {
        echo "Please select a valid logo file.";
        exit;
    }

    // Database connection (replace with your actual connection)
    $conn = new mysqli('localhost', 'root', '', 'z');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query to insert the page data
    $stmt = $conn->prepare("INSERT INTO pages (website_name, page_title, description, logo_path) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $websiteName, $pageTitle, $description, $logoPath);

    // Execute the query and check for success
    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error saving to database: " . $stmt->error;
        header("Location: index.php");
        exit;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
