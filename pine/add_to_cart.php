<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $quantity = 1; // Default quantity

    // Retrieve product details
    $sql = "SELECT name, price FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        $product_name = $product['name'];
        $product_price = $product['price'];
        $subtotal = $quantity * $product_price;

        // Check if the product is already in the cart
        $sql_check_cart = "SELECT * FROM cart WHERE product_id = ?";
        $stmt_check_cart = $conn->prepare($sql_check_cart);
        $stmt_check_cart->bind_param("i", $product_id);
        $stmt_check_cart->execute();
        $result_check_cart = $stmt_check_cart->get_result();

        if ($result_check_cart->num_rows > 0) {
            // Update the quantity and subtotal if the product is already in the cart
            $sql_update_cart = "UPDATE cart SET quantity = quantity + 1, subtotal = subtotal + ? WHERE product_id = ?";
            $stmt_update_cart = $conn->prepare($sql_update_cart);
            $stmt_update_cart->bind_param("ii", $product_price, $product_id);
            $stmt_update_cart->execute();
        } else {
            // Insert into cart
            $sql_add_to_cart = "INSERT INTO cart (product_id, product_name, product_price, quantity, subtotal) VALUES (?, ?, ?, ?, ?)";
            $stmt_add_to_cart = $conn->prepare($sql_add_to_cart);
            $stmt_add_to_cart->bind_param("isiii", $product_id, $product_name, $product_price, $quantity, $subtotal);
            $stmt_add_to_cart->execute();
        }

        // Update cart count in session
        $sql = "SELECT SUM(quantity) AS total_quantity FROM cart";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $_SESSION['cart_count'] = $row['total_quantity'];
    } else {
        echo "Produk tidak ditemukan.";
    }

    $stmt->close();
}
$conn->close();

header("Location: index.php");
exit();
?>
