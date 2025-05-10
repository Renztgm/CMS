<?php
include('db.php');

// Create enrollment table
$sql = "CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_id INT,
    page_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- Personal Information
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    date_of_birth DATE,
    gender ENUM('Male', 'Female', 'Other'),
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    -- Educational Information
    previous_school VARCHAR(100),
    last_grade_level VARCHAR(50),
    gpa DECIMAL(3,2),
    -- Guardian/Parent Information
    guardian_name VARCHAR(100),
    guardian_relationship VARCHAR(50),
    guardian_phone VARCHAR(20),
    guardian_email VARCHAR(100),
    guardian_address TEXT,
    -- Course Information
    course_applied VARCHAR(100),
    preferred_start_date DATE,
    additional_notes TEXT,
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE,
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "Enrollment table created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?> 