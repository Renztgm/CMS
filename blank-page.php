<?php
session_start();

  // Include your database connection
  include('db.php');
   $a = 17;
    $id = $a;
    $is = $a;
  // Check if 'id' is passed in the URL
  if ($is === $id) {
    $page_id = $is; // Get the page ID from the URL
    
    // Prepare SQL query to fetch multiple columns
    $stmt = $conn->prepare("SELECT website_name, page_title, logo_path FROM pages WHERE id = ?");
    
    // Bind the 'id' parameter
    $stmt->bind_param("i", $page_id);

    // Execute the statement
    $stmt->execute();

    // Bind the result to variables
    $stmt->bind_result($website_name, $page_title, $logo_path);  // Original column names

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
// Database connection (make sure you replace this with your actual database connection)
$mysqli = new mysqli("localhost", "root", "", "z");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Assuming you're getting $page_id from the URL or another source
$page_id = 17;  // Or use $_POST['page_id'] if it's sent via POST

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
            width: 50%;
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
            width: 20%;
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
            width: 20%;
            height: 90vh;
            margin: 0;
        }
        .viewing {
            overflow: auto;
            padding: 30px;
            width: 60%;
            height: 90vh;
        }
    </style>
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
</head>
<body>
    <div class="top-nav">
        <div class="left-top-nav">
        <a href="index.php">
            <img src="img\home.svg" alt=""width="20px" height="auto">
            </a>
            <?php echo "<img src='{$logo}' width='20'>"; ?>
            <p><?php echo $site_name ?></p>
            <input type="text" id="page-id" value="<?php echo $a; ?>">

        </div>
        <div class="right-top-nav">
            <img src="img\undo.svg" alt=""width="20px" height="auto">
            <img src="img\redo.svg" alt=""width="20px" height="auto">
            <img src="img\refresh.svg" alt=""width="20px" height="auto">
            <button type="submit" id="save_in_db">Save in DB</button>
            <p>5</p> 
        </div>
    </div>
    <main>
        <div class="column">
            <p>left Column</p>
        </div>


        <div class="viewing" id="viewing-area">
            <?php foreach ($sections as $section): ?>
                <div class="section" style="width: <?php echo htmlspecialchars($section['width']); ?>; height: <?php echo htmlspecialchars($section['height']); ?>; background-color: <?php echo htmlspecialchars($section['background_color']); ?>; padding: <?php echo htmlspecialchars($section['padding']); ?>; margin: <?php echo htmlspecialchars($section['margin']); ?>; border: <?php echo htmlspecialchars($section['border_value']) . ' ' . htmlspecialchars($section['border_style']) . ' ' . htmlspecialchars($section['border_color']); ?>; border-radius: <?php echo htmlspecialchars($section['border_radius']); ?>; display: flex; align-items: <?php echo htmlspecialchars($section['align_items']); ?>; justify-content: <?php echo htmlspecialchars($section['justify_content']); ?>;">
                    <h3><?php echo htmlspecialchars($section['name']); ?></h3>
                    <p>Type: <?php echo htmlspecialchars($section['type']); ?></p>
                    <p>Align Items: <?php echo htmlspecialchars($section['align_items']); ?></p>
                    <p>Justify Content: <?php echo htmlspecialchars($section['justify_content']); ?></p>
                    <p>Created At: <?php echo htmlspecialchars($section['created_at']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <aside>
            <div class="tabs">
                <button class="tab-button active" onclick="openTab(event, 'insert')">Insert</button>
                <button class="tab-button" onclick="openTab(event, 'pages')">Pages</button>
                <button class="tab-button" onclick="openTab(event, 'settings')">Settings</button>
            </div>

            <div id="insert" class="tab-content active">
                <button id="add-element-btn" class="buttonbox">+</button>
                <div id="site-structure" style="width: 100%;">
            </div>

            <!-- Modal for selecting element type -->
            <div id="section-modal" class="modal">
                <div class="modal-content">
                    <span class="close-btn">&times;</span>
                    <h2>Create Section</h2>
                    <form id="section-form" action="save_section.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
                        <label for="section-name">Section Name:</label>
                        <input type="text" id="section-name" name="section-name" required>

                        <?php
                        // Assuming you already have the current page_id (from the form or session)
                        // $page_id = isset($_GET['page_id']) ? $_GET['page_id'] : null;  // For example, passed as a GET parameter
                         $page_id = 17;
                        if ($page_id) {
                            // Database connection
                            $mysqli = new mysqli("localhost", "root", "", "z");

                            if ($mysqli->connect_error) {
                                die("Connection failed: " . $mysqli->connect_error);
                            }

                            // Fetch sections associated with the current page_id (excluding "None" option)
                            $query = "SELECT id, name FROM sections WHERE page_id = ?";
                            $stmt = $mysqli->prepare($query);
                            $stmt->bind_param("i", $page_id);  // Bind page_id to the query
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
                            <option value="parent">Parent</option>
                            <option value="none">None</option>
                            <?php foreach ($sections as $section): ?>
                                <option value="<?php echo htmlspecialchars($section['id']); ?>"><?php echo htmlspecialchars($section['name']); ?></option>
                            <?php endforeach; ?>
                        </select><br>



                        <label for="section-bg">Background Color:</label>
                        <input type="color" id="section-bg" name="section-bg" value="#ffffff">
                        <div id="parent-selector" style="display: none;">
                            <label for="parent-section">Select Parent Section:</label>
                            <select id="parent-section" name="parent-section">
                                <!-- Dynamically filled -->
                            </select>
                        </div><br>

                        <label for="section-width">Width:</label>
                        <input type="text" id="section-width" name="section-width" placeholder="e.g. 100%, 300px">
                        <select name="width-unit">
                            <option></option>
                            <option>auto</option>
                            <option>%</option>
                            <option>px</option>
                            <option>vh</option>
                            <option>rem</option>
                        </select><br>

                        <label for="section-height">Height:</label>
                        <input type="text" id="section-height" name="section-height" placeholder="e.g. 200px">
                        <select name="height-unit">
                            <option></option>
                            <option>auto</option>
                            <option>px</option>
                            <option>vh</option>
                            <option>rem</option>
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
                        <input type="number" name="padding-value">
                        <select name="padding-unit">
                            <option>%</option>
                            <option>px</option>
                            <option>vh</option>
                            <option>rem</option>
                        </select><br>

                        <label>Margin:</label>
                        <input type="number" name="margin-value">
                        <select name="margin-unit">
                            <option>%</option>
                            <option>px</option>
                            <option>vh</option>
                            <option>rem</option>
                        </select><br>

                        <fieldset>
                            <legend>Border:</legend>
                            <input type="number" name="border-value">
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
                            <input type="number" name="border-radius-value">
                            <select name="border-radius-unit">
                                <option>%</option>
                                <option>px</option>
                            </select>
                        </fieldset>

                        <fieldset>
                            <legend>Insert Element:</legend>
                            <button type="button" class="add-object" data-object="text">Text</button>
                            <button type="button" class="add-object" data-object="image">Image</button>
                            <button type="button" class="add-object" data-object="button">Button</button>
                            <button type="button" class="add-object" data-object="dropdown">Dropdown</button>
                            <button type="button" class="add-object" data-object="link">Link</button>
                        </fieldset>

                        <!-- Placeholder for dynamic element customization -->
                        <div id="element-options"></div>

                        <br>
                        <button type="submit" class="create-section-btn" id="save_in_db">Create Section</button>
                    </form>
                </div>
            </div>


            <div id="pages" class="tab-content">
                <p>Tab2</p>
            </div>
            <div id="settings" class="tab-content">
                <p>Tab3</p>
            </div>
        </aside>

    </main>
</body>

<script>// Add event listeners to buttons for adding elements
    document.getElementById('add-element-btn').addEventListener('click', function () {
  document.getElementById('section-modal').style.display = 'block';
});

// Close modal when clicking X
document.querySelector('.close-btn').addEventListener('click', function () {
  document.getElementById('section-modal').style.display = 'none';
});
</script>
<script>
document.getElementById("save_in_db").addEventListener("click", function(event){
    event.preventDefault();
    document.getElementById("section-form").submit(); // Submit the form manually
});



</script>

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
    >.tab-content.active {
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
 /* Modal background */
 .modal {
        display: none; /* Hidden by default */
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
         /* Enable scroll if needed */
        overflow-x: visible;
        position: absolute;
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
</html>