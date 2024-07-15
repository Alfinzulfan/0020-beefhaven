<?php
include 'db_connect.php';
session_start();

if (isset($_GET['update_cart']) && isset($_GET['cart_id']) && isset($_GET['quantity'])) {
    $cart_id = $_GET['cart_id'];
    $quantity = $_GET['quantity'];

    // Lakukan query UPDATE sesuai dengan cart_id dan quantity yang baru
    $sql = "UPDATE cart SET quantity = $quantity, subtotal = product_price * $quantity WHERE cart_id = $cart_id";

    if ($conn->query($sql) === TRUE) {
        echo "Keranjang berhasil diperbarui";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
header('Location: cart.php');
exit();
?>
