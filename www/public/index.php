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

$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Product List</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>

<header>
  <div class="header-left">Product List</div>
  <div class="header-right">
    <button id="add-product-btn" onclick="location.href='add-product.php'">Add</button>
    <button type="submit" form="product_list_form" id="delete-product-btn">Mass Delete</button>
  </div>
</header>

<form id="product_list_form" method="POST" action="delete-product.php">
  <div class="product-grid">
    <?php foreach ($products as $row): ?>
      <div class="product-item">
        <input type="checkbox" class="delete-checkbox" name="delete_ids[]" value="<?= htmlspecialchars($row['sku']) ?>">
        <div><?= htmlspecialchars($row['sku']) ?></div>
        <div><?= htmlspecialchars($row['name']) ?></div>
        <div><?= number_format((float)$row['price'], 2) ?> $</div>
        <div>
          <?php
            if ($row['productType'] === 'DVD') {
              echo "Size: " . htmlspecialchars($row['size']) . " MB";
            } elseif ($row['productType'] === 'Book') {
              echo "Weight: " . htmlspecialchars($row['weight']) . " KG";
            } elseif ($row['productType'] === 'Furniture') {
              echo "Dimensions: " . 
                htmlspecialchars($row['height']) . "x" . 
                htmlspecialchars($row['width']) . "x" . 
                htmlspecialchars($row['length']);
            }
          ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</form>

<footer>Scandiweb Test assignment</footer>

</body>
</html>
