<?php
// Database connection
$mysqli = new mysqli("localhost", "root", "", "z");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if page ID is passed
if (isset($_GET['id'])) {
    $page_id = (int)$_GET['id'];

    // Fetch sections
    $query = "SELECT * FROM sections WHERE page_id = ? ORDER BY parent_id, created_at";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $page_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Store sections
    $sections = [];
    while ($row = $result->fetch_assoc()) {
        $sections[] = $row;
    }
    $stmt->close();

    
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            margin: 0;
            padding:0;
            font-family: Arial, sans-serif;
        }

        .top-nav {
            margin-bottom: 5px;
            overflow: hidden;
            display: flex;
            align-items: center;
            padding: 0 50px;
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
        }

        .right-top-nav {
            display: flex;
            align-items: center;
            width: 15%;
            justify-content: space-between;
        }

        .viewing {
            padding: 0;
            overflow: auto;
            text-decoration: none;
        }

        .viewing > div {
            margin-bottom: 10px;
        }
        .viewing a{
            text-decoration: none;
            color: #000;
        }
    </style>
</head>
<body>

    <div class="top-nav">
        <div class="left-top-nav">
            <a href="edited-page.php?id=<?php echo $page_id; ?>">
                <img src="img/back.svg" alt="" width="20px">
            </a>
            <input type="text" id="page-id" value="<?php echo $page_id; ?>">
        </div>
        <div class="right-top-nav">
            <img src="img/refresh.svg" alt="" width="20px">
        </div>
    </div>

    <?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$pdo = new PDO('mysql:host=localhost;dbname=z', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Set your specific page_id here
$page_id = (int)$_GET['id'];

// Fetch sections for this page_id
$query = 'SELECT * FROM sections WHERE page_id = :page_id ORDER BY parent_id, created_at';
$stmt = $pdo->prepare($query);
$stmt->bindParam(':page_id', $page_id, PDO::PARAM_INT);
$stmt->execute();
$sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all section elements (we’ll filter them after)
$elementsQuery = 'SELECT * FROM section_elements';
$elementsStmt = $pdo->query($elementsQuery);
$section_elements = $elementsStmt->fetchAll(PDO::FETCH_ASSOC);

// Group sections by parent_id
function groupSectionsByParent($sections) {
    $grouped = [];
    foreach ($sections as $section) {
        $parent = $section['parent_id'] ?? 0;
        $grouped[$parent][] = $section;
    }
    return $grouped;
}

// Group section elements by section_id (filtered to only sections in this page)
function groupElementsBySection($elements, $sections) {
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
function renderElements($section_id, $grouped_elements) {
    if (!isset($grouped_elements[$section_id])) return '';

    $html = '';
    foreach ($grouped_elements[$section_id] as $el) {
        $style = 'style="width: ' . htmlspecialchars($el['width']) . ';
                         height: ' . htmlspecialchars($el['height']) . ';
                         padding: ' . htmlspecialchars($el['padding']) . ';
                         margin: ' . htmlspecialchars($el['margin']) . ';
                         border: ' . htmlspecialchars($el['border']) . ';
                         border-radius: ' . htmlspecialchars($el['border_radius']) . ';
                         text-align: ' . htmlspecialchars($el['alignment']) . ';"';

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
            default:
                $html .= '<div ' . $style . '>Unknown element type</div>';
                break;
        }
    }

    return $html;
}

// Renders the sections and recursively their child sections and elements
function renderSections($parent_id, $grouped_sections, $grouped_elements) {
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

        // $html .= '<strong>' . htmlspecialchars($section['name']) . '</strong>';
        $html .= renderElements($section['id'], $grouped_elements);
        $html .= renderSections($section['id'], $grouped_sections, $grouped_elements);
        $html .= '</div>';
    }

    return $html;
}


$grouped_sections = groupSectionsByParent($sections);
$grouped_elements = groupElementsBySection($section_elements, $sections);
?>


<div class="viewing" id="viewing-area">
    <?= renderSections(0, $grouped_sections, $grouped_elements) ?>
</div>


</body>
</html>

<?php
} else {
    echo "No page ID provided.";
}
?>
