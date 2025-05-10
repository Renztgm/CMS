<?php
// Example: Assuming $page_id is set, e.g. from a GET parameter or session
$page_id = $_GET['page_id']; // OR from $_SESSION or hardcoded
include 'db.php'; // your DB connection script

// Fetch only sections connected to this page
$sql = "SELECT section_id, section_name FROM sections WHERE page_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $page_id);
$stmt->execute();
$result = $stmt->get_result();
?>