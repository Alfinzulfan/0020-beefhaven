<?php
include 'db_connect.php';
session_start();

$cart_id = $_GET['cart_id'];

$sql = "DELETE FROM cart WHERE cart_id = $cart_id";

if ($conn->query($sql) === TRUE) {
    echo "Item berhasil dihapus dari keranjang";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
header('Location: cart.php');
exit();
?>
