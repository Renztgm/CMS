<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $websiteName = $_POST['website_name'];
    $pageTitle = $_POST['filename'];
    $description = $_POST['description'];

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $logoName = basename($_FILES['logo']['name']);
        $targetFile = $uploadDir . $logoName;

        if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFile)) {
            echo "<h2>New Page Created!</h2>";
            echo "<p><strong>Website Name:</strong> $websiteName</p>";
            echo "<p><strong>Page Title:</strong> $pageTitle</p>";
            echo "<p><strong>Description:</strong> $description</p>";
            echo "<p><strong>Logo:</strong><br><img src='$targetFile' width='100'></p>";
        } else {
            echo "Failed to upload logo.";
        }
    } else {
        echo "Please select a valid logo file.";
    }
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TheTestWebsite - Home</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .top-nav {
      overflow: hidden; 
      display: flex;
      align-items: center;
      justify-content: space-around;
      padding: 0 10px;
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
    .top-nav input[type="text"] {
      padding: 10px;
      margin: 8px 0;
      box-sizing: border-box;
      border: 1px solid #ccc;
      border-radius: 25px;
      width: 500px;
    }
    .top-nav input[type="text"]:focus {
      border: 1px solid #7e36d8;
      outline: none;
    }
    .startTemplate {
      overflow-x: auto;
      padding-left: 150px;
      padding-right: 150px;
      display: flex;
      background-color: #F8F4E1;
      justify-content: flex-start;  
      align-items: center;
      height: 50vh;
      gap:10px;
    }

    .contentBox {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      width: 200px;
      height: 125px;
      border: 1px solid #ccc;
      text-decoration: none;
      background-color: #f0f0f0;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .contentBox:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
    .contentCreated {
      padding-left: 150px;
      padding-right: 150px;
      margin-bottom: 150px;

    }
    .recents {
      display: flex;
      flex-wrap: wrap;  
      background-color: #fff;
      justify-content: flex-start;
      align-items: center;
      height: 50vh;
      gap:10px;
    }
    .contentCreatedBox {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      width: 200px;
      height: 200px;
      border: 1px solid #ccc;
      text-decoration: none;
      background-color: #f0f0f0;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .box a {
      text-decoration: none;
      margin: 5px;
    }
    .buttonbox{
      border: none;
      background: none;
      padding: 10px;
    }

    .box p {
      margin: 0;
      padding: 0;
    }

    .contentCreatedBox:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
  </style>
  <style>
    .modal {
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background-color: rgba(0,0,0,0.4);
  }

  .modal-content {
    background-color: #fefefe;
    margin: 10% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 400px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
  }

  .close-btn {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
  }

  .close-btn:hover {
    color: #000;
  }

  </style>
</head>
<body>

  <div class="top-nav">
    <a href="index.php" class="active">The Test Website</a>
    <input type="text" placeholder="Search..">
    <a href="#">My Account</a>
  </div>
  <main>
    <div class="startTemplate">
      <div class="box">
          <a href="javascript:void(0);" class="contentBox" onclick="openModal()">
            <img src="img\add-blank-page.svg" alt="" width="25" height="auto"/>
          </a>
          <p>Blank Page</p>
      </div>
    </div>

    <div id="infoModal" class="modal" style="display: none;">
      <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2>Create a New Page</h2>
        <form action="add-page.php" method="POST" enctype="multipart/form-data">
          <label for="website_name">Website Name:</label><br>
          <input type="text" id="website_name" name="website_name" required><br><br>

          <label for="filename">Page Title:</label><br>
          <input type="text" id="filename" name="filename" required><br><br>

          <label for="description">Description:</label><br>
          <textarea id="description" name="description" rows="3" required></textarea><br><br>

          <label for="logo">Upload Logo:</label><br>
          <input type="file" id="logo" name="logo" accept="image/*" required><br><br>

          <button type="submit">Create Page</button>
        </form>
      </div>
    </div>


    <div class="contentCreated">
      <h2>Recents</h2>
      <div class="recents">
        
      <?php 
        $result = $conn->query("SELECT * FROM pages ORDER BY created_at DESC");
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<div class='box'>";
              echo "<a href='edited-page.php?id={$row['id']}'>";
              echo "<div class='contentCreatedBox'>"; 
              echo "<img src='{$row['logo_path']}' width='50'><br>";
              echo "</div>";
              echo "</a>"; 
              echo "<p><b>{$row['website_name']}</b></p>";
              echo "<p>- {$row['page_title']}</p>";

              // 🗑️ Delete form
              echo "<form action='delete_page.php' method='POST' onsubmit='return confirm(\"Are you sure you want to delete this page?\");'>";
              echo "<input type='hidden' name='page_id' value='{$row['id']}'>";
              echo "<button type='submit' class='buttonbox' style='cursor:pointer;'><img src='img\delete.svg' alt='' width='15' height='auto'/></button>";
              echo "</form>";

            echo "</div>";
          }
        } else {
          echo "<div style='text-align:center; padding: 50px; width: 100%;'>";
          echo "<h3>No pages created yet.</h3>";
          echo "<button onclick='openModal()' style='padding: 10px 20px; background-color: #7e36d8; color: white; border: none; border-radius: 5px; cursor: pointer;'>Create a website now!</button>";
          echo "</div>";
        }
      ?>

      </div>
    </div>
  </main>


</body>
<script>
  function openModal() {
    document.getElementById("infoModal").style.display = "block";
  }

  function closeModal() {
    document.getElementById("infoModal").style.display = "none";
  }

  window.onclick = function(event) {
    const modal = document.getElementById("infoModal");
    if (event.target == modal) {
      closeModal();
    }
  }
</script>

<?php
$conn->close();
?>
</html>
