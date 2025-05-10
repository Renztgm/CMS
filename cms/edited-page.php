<?php
include('db.php');
// Check if 'id' is passed in the URL
if (isset($_GET['id'])) {
    $page_id = $_GET['id']; // Get the page ID from the URL
    $stmt = $conn->prepare("SELECT website_name, page_title, logo_path FROM pages WHERE id = ?");
    $stmt->bind_param("i", $page_id);
    $stmt->execute();
    $stmt->bind_result($website_name, $page_title, $logo_path);

    // Fetch the result
    if ($stmt->fetch()) {
        // Rename the variables for clarity
        $site_name = $website_name;  // Rename website_name to site_name
        $title = $page_title;        // Rename page_title to title
        $logo = $logo_path;          // Rename logo_path to logo


    } else {
        echo "<p>Page not found.</p>";
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo "<p>No page ID provided.</p>";
}
?>

<?php
// Database connection
$mysqli = new mysqli("localhost", "root", "", "z");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$page_id = $_GET['id'] ?? 0; // Default to 0 if 'id' is not set
$query = "SELECT * FROM sections WHERE page_id = ?";  // Fetch sections for the specific page
$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $page_id);  // Bind the page_id value
$stmt->execute();
$result = $stmt->get_result();

// Fetch the results and generate HTML content
$sections = [];
while ($row = $result->fetch_assoc()) {
    $sections[] = $row;
}
$stmt->close();
?>

<?php
// Fetch uploaded images for the page
$image_query = "SELECT file_path, width, height FROM image WHERE section_id IN (SELECT id FROM sections WHERE page_id = ?)";
$image_stmt = $conn->prepare($image_query);
$image_stmt->bind_param("i", $page_id);
$image_stmt->execute();
$image_result = $image_stmt->get_result();

if ($image_result->num_rows > 0) {
    echo "<h2>Uploaded Images:</h2>";
    while ($image = $image_result->fetch_assoc()) {
        $file_path = htmlspecialchars($image['file_path']);
        $width = htmlspecialchars($image['width']);
        $height = htmlspecialchars($image['height']);
        echo "<img src='$file_path' style='width: $width; height: $height;' alt='Uploaded Image'>";
    }
} else {
    echo "<p>No images uploaded for this page.</p>";
}

$image_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_name, " - ", $title; ?></title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            overflow: hidden;
        }

        .top-nav {
            margin: 0;
            overflow: hidden;
            display: flex;
            align-items: center;
            padding: 0 50px 0 50px;
            justify-content: space-between;
            border-bottom: 1px solid #ccc;
        }

        .top-nav a {
            color: #6B7A99;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 17px;
            transition: background-color 0.3s;
        }

        .top-nav a:hover {
            color: #6B7A99;
        }

        .top-nav a.active {
            color: #7e36d8;
        }

        .left-top-nav {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: space-between;
        }

        .right-top-nav {
            display: flex;
            align-items: center;
            width: 30%;
            justify-content: space-between;
        }

        main {
            margin: 0;
            display: flex;
            justify-content: space-between;
        }

        aside {
            border-left: 1px solid #ccc;
            list-style-type: none;
            padding: 0;
            width: 15%;
            height: 90vh;
            margin: 0;
        }

        .buttonbox {
            margin: 10px;
            width: 90%;
            height: 50px;
        }

        aside ul {
            margin: 0;
            padding: 10px;
            align-items: center;
            display: flex;
            justify-content: space-evenly;
            list-style-type: none;
            border-bottom: 1px solid #ccc;
        }

        .column {
            border-right: 1px solid #ccc;
            list-style-type: none;
            padding-left: 20px;
            width: 15%;
            height: 90vh;
            margin: 0;
        }

        .viewing {
            overflow-y: auto;
            overflow-x: auto;
            padding: 30px;
            width: 60%;
            height: 80vh;
            text-decoration: none;
        }

        .viewing a {
            text-decoration: none;
            color: #000;
        }
    </style>
    <style>
    .tab-content {
        display: none;
        border-top: none;
    }

    .tab-button {
        border: none;
        outline: none;
        cursor: pointer;
        padding: 10px 15px;
    }

    .tab-button.active {
        border-bottom: 2px solid #000;
        color: #000;
    }

    .tab-content.active {
        display: block;
    }

    .tabs {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #ccc;
    }

    .tabs button {
        flex: 1;
        padding: 10px;
        text-align: center;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .tabs button:hover {
        background-color: #ddd;
    }
</style>
<style>
    /* Modal Background */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    /* Modal Box */
    .modal-box {
        overflow-y: scroll;
        height: 80vh;
        background: #fff;
        padding: 20px;
        width: 400px;
        height: 90vh;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    .modal-box h2 {
        margin-top: 0;
    }

    .modal-box input,
    .modal-box select {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
    }

    .modal-box button {
        padding: 8px 12px;
        margin-top: 5px;
    }

    .modal-open {
        display: flex;
    }
</style>
<style>
    /* Modal background */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    /* Modal content */
    .modal-content {
        position: absolute;
        overflow-y: scroll;
        height: 80vh;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        width: 300px;
    }

    /* Close button */
    .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 20px;
        cursor: pointer;
    }

    /* Button for selecting element */
    .element-option {
        width: 100%;
        padding: 10px;
        margin: 5px 0;
        text-align: left;
        cursor: pointer;
    }
</style>

<style>
    /* Basic Styling */
    .container {
        margin-top: 20px;
        overflow-y: auto;
        height: 85vh;
    }

    .card-header {
        font-size: 1.25rem;
        font-weight: bold;
    }

    .card-body {
        padding: 15px;
    }

    .card {
        /* border-radius: 10px; */
        border-bottom: 1px solid #ccc;
    }

    /* Modal Styling */
    /* .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    width: 50%;
} */

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .close-btn {
        font-size: 1.5rem;
        cursor: pointer;
    }

    .modal-body {
        margin-top: 15px;

    }

    .modal-footer {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
    }

    /* button {
    margin: 0 5px;
} */
</style>
</head>


<body>
    <div class="top-nav">
        <div class="left-top-nav">
            <a href="index.php">
                <img src="img\home.svg" alt="" width="20px" height="auto">
            </a>
            <?php echo "<img src='{$logo}' width='20'>"; ?>
            <p><?php echo $site_name ?></p>
            <input type="text" id="page-id" value="<?php echo $page_id; ?>">

        </div>
        <div class="right-top-nav">
            <a href="edited-page.php?id=<?php echo $page_id; ?>">
                <img src="img\refresh.svg" alt="" width="20px" height="auto">
            </a>
            <a href="exampleviewing.php?id=<?php echo $page_id; ?>">
                <img src="img\preview.svg" alt="" width="20px" height="auto">
            </a>

        </div>
    </div>

    <?php if (isset($_GET['enrollment_success'])): ?>
    <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 15px; margin: 10px; border-radius: 4px;">
        Enrollment form submitted successfully!
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['enrollment_error'])): ?>
    <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 15px; margin: 10px; border-radius: 4px;">
        Error submitting enrollment form: <?php echo htmlspecialchars($_GET['enrollment_error']); ?>
    </div>
    <?php endif; ?>

    <main>
        <?php
        $mysqli = new mysqli('localhost', 'root', '', 'z');
        $page_id = $_GET['id'] ?? 0;

        // Fetch sections for this page
        $query = "SELECT * FROM sections WHERE page_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $page_id);
        $stmt->execute();
        $sections_result = $stmt->get_result();
        $sections = $sections_result->fetch_all(MYSQLI_ASSOC);

        // Fetch section elements
        $elements_result = $mysqli->query("SELECT * FROM section_elements");
        $elements = $elements_result->fetch_all(MYSQLI_ASSOC);

        // Group elements by section_id
        $grouped_elements = [];
        foreach ($elements as $element) {
            $grouped_elements[$element['section_id']][] = $element;
        }
        ?>


        <div class="column">
            <div class="container">
                <?php foreach ($sections as $section): ?>
                    <div class="card my-3">
                        <div class="card-header bg-light">
                            <p><?= htmlspecialchars($section['name']) ?></p>
                            <p style="font-size: 10px;">(SID: <?= $section['id'] ?>)</p>
                            <button class="btn edit-section-btn" data-id="<?= $section['id'] ?>">Edit Section</button>
                            <input type="hidden" name="id" value="<?= $section['id'] ?>">
                            <form action="delete-section.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $section['id'] ?>">
                                <input type="hidden" name="page_id" value="<?= $section['page_id'] ?>">
                                <button type="submit" onclick="return confirm('Delete this section and its elements?')">🗑️ Delete Section</button>
                            </form>

                        </div>

                        <div class="card-body">
                            <?php foreach ($grouped_elements[$section['id']] ?? [] as $element): ?>
                                <div class="border rounded p-3 mb-3">
                                    <p><strong>Type:</strong> <?= htmlspecialchars($element['element_type']) ?></p>
                                    <button class="btn edit-element-btn" data-id="<?= $element['id'] ?>">Edit</button>
                                    <form action="delete-element.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $element['id'] ?>">
                                        <input type="hidden" name="page_id" value="<?= $page_id ?>">
                                        <button type="submit" onclick="return confirm('Delete this element?')">🗑️ Delete Element</button>
                                    </form>


                                </div>

                                <!-- Element Modal -->
                                <div class="modal" id="editModal<?= $element['id'] ?>">
                                    <div class="modal-content">
                                        <form action="update-element.php" method="POST">
                                            <input type="hidden" name="page_id" value="<?php echo $page_id ?>">
                                            <input type="hidden" name="id" value="<?= $element['id'] ?>">
                                            <input type="hidden" name="section_id" value="<?= $element['section_id'] ?>">

                                            <label>Element Type</label>
                                            <input type="text" name="element_type" value="<?= htmlspecialchars($element['element_type']) ?>">

                                            <label>Content</label>
                                            <textarea name="content"><?= htmlspecialchars($element['content']) ?></textarea>

                                            <label>Width</label>
                                            <input type="text" name="width" value="<?= htmlspecialchars($element['width']) ?>">

                                            <label>Height</label>
                                            <input type="text" name="height" value="<?= htmlspecialchars($element['height']) ?>">

                                            <label>Alignment</label>
                                            <input type="text" name="alignment" value="<?= htmlspecialchars($element['alignment']) ?>">

                                            <label>Padding</label>
                                            <input type="text" name="padding" value="<?= htmlspecialchars($element['padding']) ?>">

                                            <label>Margin</label>
                                            <input type="text" name="margin" value="<?= htmlspecialchars($element['margin']) ?>">

                                            <label>Border</label>
                                            <input type="text" name="border" value="<?= htmlspecialchars($element['border']) ?>">

                                            <label>Border Radius</label>
                                            <input type="text" name="border_radius" value="<?= htmlspecialchars($element['border_radius']) ?>">

                                            <label for="font-family">Font Family:</label>
                                            <select id="font-family" name="font-family">
                                                <option value="Arial" <?= $element['font_family'] === 'Arial' ? 'selected' : '' ?>>Arial</option>
                                                <option value="Verdana" <?= $element['font_family'] === 'Verdana' ? 'selected' : '' ?>>Verdana</option>
                                                <option value="Times New Roman" <?= $element['font_family'] === 'Times New Roman' ? 'selected' : '' ?>>Times New Roman</option>
                                                <option value="Georgia" <?= $element['font_family'] === 'Georgia' ? 'selected' : '' ?>>Georgia</option>
                                                <option value="Courier New" <?= $element['font_family'] === 'Courier New' ? 'selected' : '' ?>>Courier New</option>
                                            </select><br>

                                            <label for="font-size">Font Size:</label>
                                            <input type="number" id="font-size" name="font-size" value="<?= htmlspecialchars($element['font_size']) ?>">
                                            <select name="font-size-unit">
                                                <option value="px" <?= strpos($element['font_size'], 'px') !== false ? 'selected' : '' ?>>px</option>
                                                <option value="em" <?= strpos($element['font_size'], 'em') !== false ? 'selected' : '' ?>>em</option>
                                                <option value="rem" <?= strpos($element['font_size'], 'rem') !== false ? 'selected' : '' ?>>rem</option>
                                                <option value="%" <?= strpos($element['font_size'], '%') !== false ? 'selected' : '' ?>>%</option>
                                            </select><br>

                                            <button type="submit">Save</button>
                                            <button type="button" class="close-btn" data-id="<?= $element['id'] ?>">Cancel</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Section Modal (for editing section name) -->
                    <div class="modal" id="sectionModal<?= $section['id'] ?>">
                        <div class="modal-content">
                            <p><?= htmlspecialchars($section['name']) ?></p>
                            <p style="font-size: 10px;">(SID: <?= $section['id'] ?>)</p>
                            <form action="update-section.php" method="POST">
                                <input type="hidden" name="id" value="<?php echo $section['id'] ?>">
                                <input type="hidden" name="page_id" value="<?php echo $page_id ?>">
                                <input type="hidden" name="type" value="<?php echo $section['type'] ?>"><br>
                                <input type="hidden" name="parent_id" value="<?php echo $section['parent_id'] ?>"><br>
                                <label>Name:</label><input type="text" name="name" value="<?php echo $section['name'] ?>" disabled><br>

                                <label>Width:</label><input type="text" name="width" value="<?php echo $section['width'] ?>"><br>
                                <label>Height:</label><input type="text" name="height" value="<?php echo $section['height'] ?>"><br>
                                <label>Align Items:</label><input type="text" name="align_items" value="<?php echo $section['align_items'] ?>"><br>
                                <label>Justify Content:</label><input type="text" name="justify_content" value="<?php echo $section['justify_content'] ?>"><br>
                                <label>Padding:</label><input type="text" name="padding" value="<?php echo $section['padding'] ?>"><br>
                                <label>Margin:</label><input type="text" name="margin" value="<?php echo $section['margin'] ?>"><br>
                                <label>Background Color:</label><input type="text" name="background_color" value="<?php echo $section['background_color'] ?>"><br>
                                <label>Border Value:</label><input type="text" name="border_value" value="<?php echo $section['border_value'] ?>"><br>
                                <label>Border Style:</label><input type="text" name="border_style" value="<?php echo $section['border_style'] ?>"><br>
                                <label>Border Color:</label><input type="text" name="border_color" value="<?php echo $section['border_color'] ?>"><br>
                                <label>Border Radius:</label><input type="text" name="border_radius" value="<?php echo $section['border_radius'] ?>"><br>
                                <label>Flex Direction:</label><input type="text" name="flex_direction" value="<?php echo $section['flex_direction'] ?>"><br>

                                <label for="font-family">Font Family:</label>
                                <select id="font-family" name="font-family">
                                    <option value="Arial">Arial</option>
                                    <option value="Verdana">Verdana</option>
                                    <option value="Times New Roman">Times New Roman</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Courier New">Courier New</option>
                                </select><br>

                                <label for="font-size">Font Size:</label>
                                <input type="number" id="font-size" name="font-size" value="16">
                                <select name="font-size-unit">
                                    <option value="px">px</option>
                                    <option value="em">em</option>
                                    <option value="rem">rem</option>
                                    <option value="%">%</option>
                                </select><br>

                                <button type="submit">Update Section</button>
                            </form>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>


        <?php
        // Fetch sections for the specific page
        $query = "SELECT * FROM sections WHERE page_id = ? ORDER BY parent_id, created_at";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $page_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $sections = $result->fetch_all(MYSQLI_ASSOC);

        // Fetch all section elements (we'll filter them after)
        $elementsQuery = "SELECT * FROM section_elements";
        $elementsResult = $mysqli->query($elementsQuery);
        $section_elements = $elementsResult->fetch_all(MYSQLI_ASSOC);

        // Group sections by parent_id
        function groupSectionsByParent($sections)
        {
            $grouped = [];
            foreach ($sections as $section) {
                $parent = $section['parent_id'] ?? 0;
                $grouped[$parent][] = $section;
            }
            return $grouped;
        }

        // Group section elements by section_id (filtered to only sections in this page)
        function groupElementsBySection($elements, $sections)
        {
            $valid_section_ids = array_column($sections, 'id');
            $grouped = [];
            foreach ($elements as $el) {
                if (in_array($el['section_id'], $valid_section_ids)) {
                    $grouped[$el['section_id']][] = $el;
                }
            }
            return $grouped;
        }

        // Renders the elements in each section
        function renderElements($section_id, $grouped_elements)
        {
            if (!isset($grouped_elements[$section_id])) return '';

            $html = '';
            foreach ($grouped_elements[$section_id] as $el) {
                // Skip rendering for empty or default content elements
                if (
                    in_array($el['element_type'], ['text', 'textfield', 'button', 'link', 'image']) &&
                    (trim($el['content']) === '' || $el['content'] === null)
                ) {
                    continue;
                }
                $style = 'style="width: ' . htmlspecialchars($el['width']) . ';
                            height: ' . htmlspecialchars($el['height']) . ';
                            padding: ' . htmlspecialchars($el['padding']) . ';
                            margin: ' . htmlspecialchars($el['margin']) . ';
                            border: ' . htmlspecialchars($el['border']) . ';
                            border-radius: ' . htmlspecialchars($el['border_radius']) . ';
                            text-align: ' . htmlspecialchars($el['alignment']) . ';
                            font-family: ' . htmlspecialchars($el['font_family']) . ';
                            font-size: ' . htmlspecialchars($el['font_size']) . ';"';

                switch ($el['element_type']) {
                    case 'text':
                        $html .= '<p ' . $style . '>' . htmlspecialchars($el['content']) . '</p>';
                        break;
                    case 'textfield':
                        $html .= '<input type="text" value="' . htmlspecialchars($el['content']) . '" ' . $style . ' />';
                        break;
                    case 'button':
                        $html .= '<button ' . $style . '>' . htmlspecialchars($el['content']) . '</button>';
                        break;
                    case 'link':
                        $html .= '<a href="' . htmlspecialchars($el['content']) . '" ' . $style . '>' . htmlspecialchars($el['content']) . '</a>';
                        break;
                    case 'image':
                        $html .= '<img src="' . htmlspecialchars($el['content']) . '" alt="Image" ' . $style . ' />';
                        break;
                    case 'enrollment_form':
                        global $page_id;
                        $html .= generateEnrollmentForm($el['section_id'], $page_id, $style);
                        break;
                    default:
                        $html .= '<div ' . $style . '>Unknown element type</div>';
                        break;
                }
            }

            return $html;
        }

        // Add the enrollment form generation function
        function generateEnrollmentForm($section_id, $page_id, $style) {
            $html = '<form action="save_enrollment.php" method="POST" class="enrollment-form" ' . $style . '>';
            $html .= '<input type="hidden" name="section_id" value="' . $section_id . '">';
            $html .= '<input type="hidden" name="page_id" value="' . $page_id . '">';
            $html .= '<div class="enrollment-container">';
            $html .= '<h2 style="text-align:center;">Student Enrollment Form</h2>';
            $html .= '<div class="form-group"><label><b>First Name:</b></label><input type="text" name="first_name" required></div>';
            $html .= '<div class="form-group"><label><b>Last Name:</b></label><input type="text" name="last_name" required></div>';
            $html .= '<div class="form-group"><label><b>Date of Birth:</b></label><input type="date" name="date_of_birth" required></div>';
            $html .= '<div class="form-group"><label><b>Gender:</b></label><select name="gender" required><option value="Male">Male</option><option value="Female">Female</option><option value="Other">Other</option></select></div>';
            $html .= '<div class="form-group"><label><b>Email:</b></label><input type="email" name="email" required></div>';
            $html .= '<div class="form-group"><label><b>Phone:</b></label><input type="tel" name="phone" required></div>';
            $html .= '<div class="form-group"><label><b>Address:</b></label><textarea name="address" required></textarea></div>';
            $html .= '<hr>';
            $html .= '<div class="form-group"><label><b>Previous School:</b></label><input type="text" name="previous_school" required></div>';
            $html .= '<div class="form-group"><label><b>Last Grade Level:</b></label><input type="text" name="last_grade_level" required></div>';
            $html .= '<div class="form-group"><label><b>GPA:</b></label><input type="number" name="gpa" step="0.01" min="0" max="4" required></div>';
            $html .= '<hr>';
            $html .= '<div class="form-group"><label><b>Guardian Name:</b></label><input type="text" name="guardian_name" required></div>';
            $html .= '<div class="form-group"><label><b>Relationship:</b></label><input type="text" name="guardian_relationship" required></div>';
            $html .= '<div class="form-group"><label><b>Guardian Phone:</b></label><input type="tel" name="guardian_phone" required></div>';
            $html .= '<div class="form-group"><label><b>Guardian Email:</b></label><input type="email" name="guardian_email" required></div>';
            $html .= '<div class="form-group"><label><b>Guardian Address:</b></label><textarea name="guardian_address" required></textarea></div>';
            $html .= '<hr>';
            $html .= '<div class="form-group"><label><b>Course Applied:</b></label><input type="text" name="course_applied" required></div>';
            $html .= '<div class="form-group"><label><b>Preferred Start Date:</b></label><input type="date" name="preferred_start_date" required></div>';
            $html .= '<div class="form-group"><label><b>Additional Notes:</b></label><textarea name="additional_notes"></textarea></div>';
            $html .= '<button type="submit" class="submit-btn">Submit Enrollment</button>';
            $html .= '</div>';
            $html .= '</form>';
            $html .= '<style>
                body {
                    background: #f7f8fa;
                }
                .enrollment-form {
                    width: 100vw;
                    min-height: 100vh;
                    display: flex;
                    justify-content: center;
                    align-items: flex-start;
                    margin: 0;
                    padding: 0;
                    background: transparent;
                }
                .enrollment-container {
                    background: #fff;
                    border: 1px solid #e0e0e0;
                    border-radius: 16px;
                    padding: 40px 32px 32px 32px;
                    box-shadow: 0 8px 32px rgba(0,0,0,0.10);
                    min-width: 320px;
                    max-width: 600px;
                    width: 100%;
                    margin-top: 48px;
                }
                .enrollment-container h2 {
                    font-size: 2rem;
                    margin-bottom: 24px;
                    text-align: center;
                    font-weight: 700;
                    color: #222;
                }
                .form-group {
                    margin-bottom: 22px;
                }
                .form-group label {
                    display: block;
                    margin-bottom: 7px;
                    font-weight: 500;
                    color: #333;
                }
                .form-group input,
                .form-group select,
                .form-group textarea {
                    width: 100%;
                    padding: 12px;
                    border: 1px solid #ccc;
                    border-radius: 6px;
                    font-size: 16px;
                    box-sizing: border-box;
                    background: #fafbfc;
                    transition: border 0.2s;
                }
                .form-group input:focus,
                .form-group select:focus,
                .form-group textarea:focus {
                    border: 1.5px solid #7e36d8;
                    outline: none;
                }
                .form-group textarea {
                    height: 70px;
                }
                .submit-btn {
                    background-color: #7e36d8;
                    color: white;
                    padding: 14px 0;
                    border: none;
                    border-radius: 6px;
                    cursor: pointer;
                    font-size: 18px;
                    margin-top: 18px;
                    width: 100%;
                    font-weight: 600;
                    box-shadow: 0 2px 8px rgba(126,54,216,0.08);
                    transition: background 0.2s;
                }
                .submit-btn:hover {
                    background-color: #5e27a6;
                }
                .enrollment-container hr {
                    margin: 28px 0;
                    border: none;
                    border-top: 1px solid #eee;
                }
                @media (max-width: 700px) {
                    .enrollment-container {
                        max-width: 98vw;
                        padding: 18px 4vw;
                        margin-top: 16px;
                    }
                    .enrollment-form {
                        min-height: unset;
                    }
                }
            </style>';
            return $html;
        }

        // Renders the sections and recursively their child sections and elements
        function renderSections($parent_id, $grouped_sections, $grouped_elements, $conn) {
            if (!isset($grouped_sections[$parent_id])) return '';

            $html = '';
            foreach ($grouped_sections[$parent_id] as $section) {
                $html .= '<div class="' . htmlspecialchars($section['name']) . '" 
                style="width: ' . htmlspecialchars($section['width']) . ';
                    height: ' . htmlspecialchars($section['height']) . ';
                    background-color: ' . htmlspecialchars($section['background_color']) . ';
                    padding: ' . htmlspecialchars($section['padding']) . ';
                    margin: ' . htmlspecialchars($section['margin']) . ';
                    border: ' . htmlspecialchars($section['border_value']) . ' ' . htmlspecialchars($section['border_style']) . ' ' . htmlspecialchars($section['border_color']) . ';
                    border-radius: ' . htmlspecialchars($section['border_radius']) . ';
                    display: flex;
                    flex-direction: ' . htmlspecialchars($section['flex_direction']) . ';
                    align-items: ' . htmlspecialchars($section['align_items']) . ';
                    justify-content: ' . htmlspecialchars($section['justify_content']) . ';
                    min-height: 50px;">';

                $html .= renderElements($section['id'], $grouped_elements, $conn);
                $html .= renderSections($section['id'], $grouped_sections, $grouped_elements, $conn);
                $html .= '</div>';
            }

            return $html;
        }


        $grouped_sections = groupSectionsByParent($sections);
        $grouped_elements = groupElementsBySection($section_elements, $sections);
        ?>
        <!-- End here for the php line 353-->



        <div class="viewing" id="viewing-area">
            <?= renderSections(0, $grouped_sections, $grouped_elements, $mysqli) ?>
        </div>

        <aside>
            <div class="tabs">
                <button class="tab-button active" onclick="openTab(event, 'insert')">Insert</button>
                <button class="tab-button" onclick="openTab(event, 'pages')">image</button>
                <button class="tab-button" onclick="openTab(event, 'settings')">Settings</button>
            </div>

            <div id="insert" class="tab-content active" style="display: block;">
                <button id="add-element-btn" class="buttonbox">Add Section</button>
                <button onclick="openModal()" class="buttonbox">Add Block</button>
            </div>

            <div id="section-modal" class="modal">
                <div class="modal-content">
                    <span class="close-btn">&times;</span>
                    <h2>Create Section</h2>
                    <form id="section-form" action="save_section.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">


                        <label for="section-name">Section Name:</label>
                        <input type="text" id="section-name" name="section-name" required>

                        <?php
                        $page_id = $_GET['id'];
                        if ($page_id) {
                            $mysqli = new mysqli("localhost", "root", "", "z");
                            if ($mysqli->connect_error) {
                                die("Connection failed: " . $mysqli->connect_error);
                            }
                            $query = "SELECT id, name FROM sections WHERE page_id = ?";
                            $stmt = $mysqli->prepare($query);
                            $stmt->bind_param("i", $page_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            // Store the section names in an array for the dropdown
                            $sections = [];
                            while ($row = $result->fetch_assoc()) {
                                $sections[] = $row;
                            }
                            $stmt->close();
                        } else {
                            // Handle the case where page_id is not provided
                            echo "Page ID is required.";
                        }
                        ?>

                        <label for="section-type">Type Section:</label>
                        <select id="section-type" name="section-type">
                            <option value="none">None</option>
                            <?php foreach ($sections as $section): ?>
                                <option value="<?php echo htmlspecialchars($section['id']); ?>"><?php echo htmlspecialchars($section['name']); ?></option>
                            <?php endforeach; ?>
                        </select><br>

                        <label for="section-bg">Background Color:</label>
                        <input type="color" id="section-bg" name="section-bg" value="#ffffff"><br>
                        <label for="section-width">Width:</label>
                        <input type="number" id="section-width" name="section-width" value="100">
                        <select name="width-unit">
                            <option>%</option>
                            <option>px</option>
                            <option>vh</option>
                            <option>rem</option>
                        </select><br>

                        <label for="section-height">Height:</label>
                        <input type="number" id="section-height" name="section-height" value="100">
                        <select name="height-unit">
                            <option>vh</option>
                            <option>px</option>
                            <option>rem</option>
                        </select><br>

                        <label for="flex-direction">flex-direction:</label>
                        <select name="flex-direction" id="flex-direction">
                            <option>row</option>
                            <option>row-reverse</option>
                            <option>column</option>
                            <option>column-reverse</option>
                        </select><br>

                        <label for="align-items">Align Items:</label>
                        <select id="align-items" name="align-items">
                            <option>stretch</option>
                            <option>center</option>
                            <option>flex-start</option>
                            <option>flex-end</option>
                            <option>baseline</option>
                        </select><br>

                        <label for="justify-content">Justify Content:</label>
                        <select id="justify-content" name="justify-content">
                            <option>flex-start</option>
                            <option>flex-end</option>
                            <option>center</option>
                            <option>space-between</option>
                            <option>space-around</option>
                            <option>space-evenly</option>
                        </select><br>

                        <label>Padding:</label>
                        <input type="number" name="padding-value" value="0">
                        <select name="padding-unit">
                            <option>%</option>
                            <option>px</option>
                            <option>vh</option>
                            <option>rem</option>
                        </select><br>

                        <label>Margin:</label>
                        <input type="number" name="margin-value" value="0">
                        <select name="margin-unit">
                            <option>%</option>
                            <option>px</option>
                            <option>vh</option>
                            <option>rem</option>
                        </select><br>

                        <fieldset>
                            <legend>Border:</legend>
                            <input type="number" name="border-value" value="0">
                            <select name="border-unit">
                                <option>%</option>
                                <option>px</option>
                                <option>vh</option>
                                <option>rem</option>
                            </select>
                            <select name="border-style">
                                <option>solid</option>
                                <option>dashed</option>
                            </select>
                            <input type="color" name="border-color">

                            <label>Border Radius:</label>
                            <input type="number" name="border-radius-value" value="0">
                            <select name="border-radius-unit">
                                <option>%</option>
                                <option>px</option>
                            </select>
                        </fieldset>

                        <button type="submit" class="create-section-btn" id="save_in_db">Create Section</button>
                    </form>
                </div>
            </div>



            <div class="modal-overlay" id="elementModal">
                <div class="modal-box">
                    <form action="save_element.php" method="POST">
                        <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
                        <h2>Add Elements</h2>

                        <label>Section ID</label>
                        <select type="number" name="section_id" required>
                            <?php foreach ($sections as $section): ?>
                                <option value="<?php echo htmlspecialchars($section['id']); ?>"><?php echo htmlspecialchars($section['name']); ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label>Element Type</label>
                        <select name="element_type">
                            <option value="button">Button</option>
                            <option value="text">Text</option>
                            <option value="textfield">Textfield</option>
                            <option value="link">Link</option>
                            <option value="image">Image</option>
                            <option value="enrollment_form">Enrollment Form</option>
                        </select>

                        <label>Content</label>
                        <input type="text" name="content" required>

                        <label>Width</label>
                        <input type="text" name="width" value="100px">

                        <label>Height</label>
                        <input type="text" name="height" value="40px">

                        <label>Alignment</label>
                        <input type="text" name="alignment" value="left">

                        <label>Padding</label>
                        <input type="text" name="padding" value="5px">

                        <label>Margin</label>
                        <input type="text" name="margin" value="5px">

                        <label>Border</label>
                        <input type="text" name="border" value="1px solid #000">

                        <label>Border Radius</label>
                        <input type="text" name="border_radius" value="5px">

                        <label for="font-family">Font Family:</label>
                        <select id="font-family" name="font-family">
                            <option value="Arial">Arial</option>
                            <option value="Verdana">Verdana</option>
                            <option value="Times New Roman">Times New Roman</option>
                            <option value="Georgia">Georgia</option>
                            <option value="Courier New">Courier New</option>
                        </select><br>

                        <label for="font-size">Font Size:</label>
                        <input type="number" id="font-size" name="font-size" value="16">
                        <select name="font-size-unit">
                            <option value="px">px</option>
                            <option value="em">em</option>
                            <option value="rem">rem</option>
                            <option value="%">%</option>
                        </select><br>

                        <button type="submit">Save</button>
                        <button type="button" onclick="closeModal()">Cancel</button>
                    </form>
                </div>
            </div>

            <div id="pages" class="tab-content">
                <!-- Image Insert Form -->
                <h3>Insert Image</h3>
                <form action="insert_image.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
                    <!-- Section ID -->
                    <div class="form-group">
                        <label>Section ID</label>
                        <select type="number" name="section_id" required>
                            <?php foreach ($sections as $section): ?>
                                <option value="<?php echo htmlspecialchars($section['id']); ?>"><?php echo htmlspecialchars($section['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Image File -->
                    <div class="form-group">
                        <label for="image">Choose Image</label>
                        <input type="file" id="image" name="image" accept="image/*" required>
                    </div>

                    <!-- Width -->
                    <div class="form-group">
                        <label for="width">Width</label>
                        <input type="text" id="width" name="width" placeholder="e.g. 100px or 100%" required>
                    </div>

                    <!-- Height -->
                    <div class="form-group">
                        <label for="height">Height</label>
                        <input type="text" id="height" name="height" placeholder="e.g. 200px or auto" required>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="button">Save Image</button>
                </form>

                <style>
                    .form-group {
                        margin-bottom: 12px;
                    }

                    label {
                        display: block;
                        margin-bottom: 4px;
                    }

                    input {
                        width: 100%;
                        padding: 6px;
                        box-sizing: border-box;
                    }

                    .button {
                        padding: 8px 14px;
                        background-color: #28a745;
                        color: white;
                        border: none;
                        cursor: pointer;
                        border-radius: 4px;
                    }

                    .button:hover {
                        background-color: #218838;
                    }
                </style>

            </div>
            <div id="settings" class="tab-content">
                <p>Tab3</p>
            </div>
        </aside>
    </main>
</body>



<script>
    function openTab(event, tabName) {
        const tabcontent = document.getElementsByClassName("tab-content");
        for (let i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        const tablinks = document.getElementsByClassName("tab-button");
        for (let i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }

        document.getElementById(tabName).style.display = "block";
        event.currentTarget.classList.add("active");
    }
</script>
<script>
    function openModal() {
        document.getElementById('elementModal').classList.add('modal-open');
    }

    function closeModal() {
        document.getElementById('elementModal').classList.remove('modal-open');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
</script>
<script>
    document.getElementById('add-element-btn').addEventListener('click', function() {
        document.getElementById('section-modal').style.display = 'block';
    });

    // Close modal when clicking X
    document.querySelector('.close-btn').addEventListener('click', function() {
        document.getElementById('section-modal').style.display = 'none';
    });


    document.getElementById('section-type').addEventListener('change', function() {
        const parentSelector = document.getElementById('parent-selector');
        if (this.value === 'child') {
            parentSelector.style.display = 'block';
        } else {
            parentSelector.style.display = 'none';
        }
    });



    document.getElementById("save_in_db").addEventListener("click", function(event) {
        if (!form.checkValidity()) {
            e.preventDefault(); // stops submission if validation fails
            alert("Please fill all required fields");
        } else {
            document.getElementById("section-form").submit();
        }


    });
</script>
<script>
    document.getElementById('add-element-btn').addEventListener('click', function() {
        document.getElementById('section-modal').style.display = 'block';
    });

    // Close modal when clicking X
    document.querySelector('.close-btn').addEventListener('click', function() {
        document.getElementById('section-modal').style.display = 'none';
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.getElementById('section-modal').style.display = 'none';
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Open modal when Edit button is clicked
        const editButtons = document.querySelectorAll('.edit-button');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modalId = `#editModal${this.getAttribute('data-id')}`;
                const modal = document.querySelector(modalId);
                modal.style.display = 'flex'; // Show the modal
            });
        });

        // Close modal when close button is clicked
        const closeButtons = document.querySelectorAll('.close-btn');
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modalId = `#editModal${this.getAttribute('data-id')}`;
                const modal = document.querySelector(modalId);
                modal.style.display = 'none'; // Hide the modal
            });
        });

        // Close modal when clicked outside the modal content
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                const modal = event.target;
                modal.style.display = 'none';
            }
        });
    });


    document.addEventListener("DOMContentLoaded", () => {
        // Handle element modals
        document.querySelectorAll('.edit-element-btn').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                document.getElementById(`editModal${id}`).style.display = 'flex';
            });
        });

        // Handle section modals
        document.querySelectorAll('.edit-section-btn').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                document.getElementById(`sectionModal${id}`).style.display = 'flex';
            });
        });

        // Close any modal
        document.querySelectorAll('.close-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const type = btn.dataset.type;
                const modalId = type === "section" ? `sectionModal${id}` : `editModal${id}`;
                document.getElementById(modalId).style.display = 'none';
            });
        });

        // Close modal if clicked outside content
        window.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal')) {
                e.target.style.display = 'none';
            }
        });
    });
</script>

</html>