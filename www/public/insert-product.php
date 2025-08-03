<?php
$conn = new mysqli("localhost", "root", "", "scandiweb");

$sku = $_POST['sku'];
$name = $_POST['name'];
$price = $_POST['price'];
$type = $_POST['productType'];

$size = $_POST['size'] ?? null;
$weight = $_POST['weight'] ?? null;
$height = $_POST['height'] ?? null;
$width = $_POST['width'] ?? null;
$length = $_POST['length'] ?? null;

$stmt = $conn->prepare("INSERT INTO products (sku, name, price, productType, size, weight, height, width, length) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssdssdddd", $sku, $name, $price, $type, $size, $weight, $height, $width, $length);
$stmt->execute();

header("Location: index.php");
