<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// PDO Database connection
$pdo = new PDO('mysql:host=localhost;dbname=z', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if page ID is passed
if (isset($_GET['id'])) {
    $page_id = (int)$_GET['id'];

    // Fetch sections for this page_id
    $stmt = $pdo->prepare('SELECT * FROM sections WHERE page_id = :page_id ORDER BY parent_id, created_at');
    $stmt->bindParam(':page_id', $page_id, PDO::PARAM_INT);
    $stmt->execute();
    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all section elements
    $elements = $pdo->query('SELECT * FROM section_elements')->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all images for this page, grouped by section_id
    $image_stmt = $pdo->prepare("SELECT section_id, file_path, width, height FROM image WHERE section_id IN (SELECT id FROM sections WHERE page_id = :page_id)");
    $image_stmt->bindParam(':page_id', $page_id, PDO::PARAM_INT);
    $image_stmt->execute();
    $image_result = $image_stmt->fetchAll(PDO::FETCH_ASSOC);

    $grouped_images = [];
    foreach ($image_result as $image) {
        $grouped_images[$image['section_id']][] = $image;
    }

    // Group sections by parent_id
    function groupSectionsByParent($sections) {
        $grouped = [];
        foreach ($sections as $section) {
            $parent = $section['parent_id'] ?? 0;
            $grouped[$parent][] = $section;
        }
        return $grouped;
    }

    // Group section elements by section_id
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

    // Render section elements
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

    // Recursive rendering of sections
    function renderSections($parent_id, $grouped_sections, $grouped_elements, $grouped_images) {
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

            // Render section elements
            $html .= renderElements($section['id'], $grouped_elements);

            // Render images
            if (isset($grouped_images[$section['id']])) {
                foreach ($grouped_images[$section['id']] as $image) {
                    $file_path = htmlspecialchars($image['file_path']);
                    $width = htmlspecialchars($image['width']);
                    $height = htmlspecialchars($image['height']);
                    $html .= "<img src='$file_path' style='width: $width; height: $height;' alt='Section Image'>";
                }
            }

            // Recursively render child sections
            $html .= renderSections($section['id'], $grouped_sections, $grouped_elements, $grouped_images);

            $html .= '</div>';
        }

        return $html;
    }

    // Group data
    $grouped_sections = groupSectionsByParent($sections);
    $grouped_elements = groupElementsBySection($elements, $sections);

    ?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .top-nav {
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            padding: 0 50px;
            justify-content: space-between;
            border-bottom: 1px solid #ccc;
        }
        .top-nav a {
            color: #6B7A99;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 17px;
        }
        .top-nav a:hover {
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
        }
        .viewing > div {
            margin-bottom: 10px;
        }
        .viewing a {
            text-decoration: none;
            color: #000;
        }
    </style>
</head>
<body>

<div class="top-nav">
    <div class="left-top-nav">
        <a href="edited-page.php?id=<?= $page_id ?>">
            <img src="img/back.svg" alt="Back" width="20px">
        </a>
        <input type="text" id="page-id" value="<?= $page_id ?>">
    </div>
    <div class="right-top-nav">
        <img src="img/refresh.svg" alt="Refresh" width="20px">
    </div>
</div>

<div class="viewing" id="viewing-area">
    <?= renderSections(0, $grouped_sections, $grouped_elements, $grouped_images) ?>
</div>

</body>
</html>

<?php
} else {
    echo "No page ID provided.";
}
?>
