<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Prepare the SQL statement
        $stmt = $conn->prepare("
            INSERT INTO enrollments (
                section_id, page_id,
                first_name, last_name, date_of_birth, gender, email, phone, address,
                previous_school, last_grade_level, gpa,
                guardian_name, guardian_relationship, guardian_phone, guardian_email, guardian_address,
                course_applied, preferred_start_date, additional_notes
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        // Bind parameters
        $stmt->bind_param("iisssssssssdssssssss",
            $_POST['section_id'],
            $_POST['page_id'],
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['date_of_birth'],
            $_POST['gender'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['address'],
            $_POST['previous_school'],
            $_POST['last_grade_level'],
            $_POST['gpa'],
            $_POST['guardian_name'],
            $_POST['guardian_relationship'],
            $_POST['guardian_phone'],
            $_POST['guardian_email'],
            $_POST['guardian_address'],
            $_POST['course_applied'],
            $_POST['preferred_start_date'],
            $_POST['additional_notes']
        );

        // Execute the statement
        if ($stmt->execute()) {
            // Success - redirect back to the page with success message
            header("Location: edited-page.php?id=" . $_POST['page_id'] . "&enrollment_success=1");
            exit();
        } else {
            throw new Exception("Error executing statement: " . $stmt->error);
        }
    } catch (Exception $e) {
        // Error - redirect back to the page with error message
        header("Location: edited-page.php?id=" . $_POST['page_id'] . "&enrollment_error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Not a POST request - redirect to home
    header("Location: index.php");
    exit();
}
?> 