<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "root", "scandiweb");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (!empty($_POST['delete_ids'])) {
  foreach ($_POST['delete_ids'] as $sku) {
    $sku = $conn->real_escape_string($sku);
    $conn->query("DELETE FROM products WHERE sku = '$sku'");
  }
}

header("Location: index.php");
exit;
