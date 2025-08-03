<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$db = 'scandiweb';
$user = 'root';
$pass = 'root'; 
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sku = $_POST['sku'] ?? '';
  $name = $_POST['name'] ?? '';
  $price = $_POST['price'] ?? '';
  $productType = $_POST['productType'] ?? '';

  $size = $_POST['size'] ?? null;
  $weight = $_POST['weight'] ?? null;
  $height = $_POST['height'] ?? null;
  $width = $_POST['width'] ?? null;
  $length = $_POST['length'] ?? null;

  $stmt = $pdo->prepare("INSERT INTO products 
    (sku, name, price, productType, size, weight, height, width, length) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

  $stmt->execute([
    $sku, $name, $price, $productType,
    $size, $weight, $height, $width, $length
  ]);

  header("Location: index.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add Product</title>
  <link rel="stylesheet" href="css/style.css" />
  <script>
    function showFields() {
      var type = document.getElementById('productType').value;
      document.getElementById('dvd_fields').style.display = (type === 'DVD') ? 'block' : 'none';
      document.getElementById('book_fields').style.display = (type === 'Book') ? 'block' : 'none';
      document.getElementById('furniture_fields').style.display = (type === 'Furniture') ? 'block' : 'none';
    }
  </script>
  <style>
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px;
      border-bottom: 1px solid #ccc;
      margin-bottom: 20px;
    }
    header h1 {
      margin: 0;
    }
    header button {
      padding: 6px 15px;
      margin-left: 10px;
      cursor: pointer;
      font-size: 14px;
    }
  </style>
</head>
<body onload="showFields()">

<header>
  <h1>Add Product</h1>
  <div>
    <button type="submit" form="product_form">Save</button>
    <button type="button" onclick="window.location.href='index.php'">Cancel</button>
  </div>
</header>

<form method="POST" action="add-product.php" id="product_form">
  <label>SKU:<br><input type="text" name="sku" required></label><br><br>
  <label>Name:<br><input type="text" name="name" required></label><br><br>
  <label>Price ($):<br><input type="number" step="0.01" name="price" required></label><br><br>

  <label>Type Switcher:<br>
    <select name="productType" id="productType" onchange="showFields()" required>
      <option value="">Select type</option>
      <option value="DVD">DVD</option>
      <option value="Book">Book</option>
      <option value="Furniture">Furniture</option>
    </select>
  </label><br><br>

  <div id="dvd_fields" style="display:none;">
    <label>Size (MB):<br><input type="number" name="size"></label><br><br>
  </div>

  <div id="book_fields" style="display:none;">
    <label>Weight (KG):<br><input type="number" step="0.01" name="weight"></label><br><br>
  </div>

  <div id="furniture_fields" style="display:none;">
    <label>Height:<br><input type="number" step="0.01" name="height"></label><br><br>
    <label>Width:<br><input type="number" step="0.01" name="width"></label><br><br>
    <label>Length:<br><input type="number" step="0.01" name="length"></label><br><br>
  </div>
</form>

<footer style="margin-top: 30px; text-align: center;">Scandiweb Test assignment</footer>

</body>
</html>
