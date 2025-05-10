<?php
    $backgroundImage =
   // Default background image
'https://media.istockphoto.com/id/543217268/photo/starry-night.jpg?s=612x612&w=0&k=20&c=XZ-7U6lX0j4Oz1XX390ht6kIw9k37SnX7aj8DTVG1T4=';
// Default condition
    $someCondition = true; 

    // Determine background image based on condition
    if ($someCondition) {
        $backgroundImage =
'https://media.istockphoto.com/id/543217268/photo/starry-night.jpg?s=612x612&w=0&k=20&c=XZ-7U6lX0j4Oz1XX390ht6kIw9k37SnX7aj8DTVG1T4=';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Background Image with PHP</title>
    <style>
.header {
  padding: 0.01px;
  text-align: center;
  background: purple;
  color: black;
}

.header h1 {
  font-size: 26px;
}

.navbar {
  background-color: whitesmoke;
  position: fixed;
  top: -50px;
  width: 100%;
  display: block;
  transition: top 0.3s;
}

.navbar a {
  float: left;
  font-size: 20px;
  color: black;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

.dropdown {
  float: left;
  overflow: hidden;
}

.dropdown .dropbtn {
  font-size: 20px;  
  border: none;
  outline: none;
  color: black;
  padding: 14px 16px;
  background-color: inherit;
  font-family: inherit;
  margin: 0;
}

.navbar a:hover, .dropdown:hover .dropbtn {
  background-color: red;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  float: none;
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
  text-align: left;
}

.dropdown-content a:hover {
  background-color: black;
}

.dropdown:hover .dropdown-content {
  display: block;
}

body {
 background-image: url('<?php echo $backgroundImage; ?>');
 background-size:cover;
 background-position: center top;
 background-repeat: no-repeat;
 --scrollbar-width: 0px;
  font-family: Arial,Helvetica,sans-serif;
  font-size: 10px;
}

.square {
  height: 1000px;
  width: 500px;
  color: darkblue;
  background: blueviolet;
align-items: center;
margin: auto;
transform:translate(-80%, 20%);
}
.square2 {
  height: 1000px;
  width: 500px;
  color: darkblue;
  background: blueviolet;
align-items: center;
margin: auto;
transform:translate(80%, -81.5%);
}
    </style>
</head>
<header>
<div class="header">
  <h1>Template for CMS</h1>
</div>
</header>
<body>

<div class="navbar">
  <a href="#home">Home</a>
  <a href="#news">News</a>
  <div class="dropdown">
    <button class="dropbtn">Dropdown 
    
    </button>
    <div class="dropdown-content">
      <a href="#">Link 1</a>
      <a href="#">Link 2</a>
      <a href="#">Link 3</a>
    </div>
  </div> 
</div>

    <div class ="square">
        <div style="padding:15px 15px 2500px;font-size:30px">
  <p><b>This is the template head text of the text menu.</b></p>
  <p>this is a template and this is where text goes</p>
</div>
    </div>

    <div class ="square2">
        <div style="padding:15px 15px 2500px;font-size:30px">
        <p><b>This is the template head text of the text menu.</b></p>
  <p>this is a template and this is where text goes</p>
</div>
    </div>

<script>
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    document.getElementById("navbar").style.top = "0";
  } else {
    document.getElementById("navbar").style.top = "-50px";
  }
}
</script>
</body>
</html>
