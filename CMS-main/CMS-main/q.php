<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$pdo = new PDO('mysql:host=localhost;dbname=z', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Set your specific page_id here
$page_id = 27;

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
                   flex-direction: column;
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
