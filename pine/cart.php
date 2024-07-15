<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <?php include 'db_connect.php'; ?>

    <div class="container mt-5">
        <h2 class="text-start display-6 fw-bolder">Cart</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 bg-white text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="whitespace-nowrap px-4 py-2 font-semibold text-gray-900">Product</th>
                        <th class="whitespace-nowrap px-4 py-2 font-semibold text-gray-900">Price</th>
                        <th class="whitespace-nowrap px-4 py-2 font-semibold text-gray-900">Qty</th>
                        <th class="whitespace-nowrap px-4 py-2 font-semibold text-gray-900">Subtotal</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody id="cartItems" class="divide-y divide-gray-200">
                    <?php
                    $sql = "SELECT * FROM cart";
                    $result = $conn->query($sql);

                    $total = 0;

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $total += $row['subtotal'];
                            echo "<tr>";
                            echo "<td class='whitespace-nowrap px-4 py-2 text-gray-700'>" . $row['product_name'] . "</td>";
                            echo "<td class='whitespace-nowrap px-4 py-2 text-gray-700'>Rp" . $row['product_price'] . "</td>";
                            echo "<td class='whitespace-nowrap px-4 py-2 text-gray-700'><input type='number' value='" . $row['quantity'] . "' min='1' max='99' class='w-16 rounded border-gray-300 text-center' onchange='updateCart(" . $row['cart_id'] . ", this.value)'></td>";
                            echo "<td class='whitespace-nowrap px-4 py-2 text-gray-700'>Rp" . $row['subtotal'] . "</td>";
                            echo "<td class='whitespace-nowrap px-4 py-2'><a href='remove_from_cart.php?cart_id=" . $row['cart_id'] . "' class='btn btn-outline-danger btn-sm'>Delete</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center px-4 py-2 text-gray-700'>Keranjang Anda kosong</td></tr>";
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end px-4 py-2 font-semibold text-gray-900">Total:</td>
                        <td id="cartTotal" class="px-4 py-2 text-gray-700">Rp<?php echo $total; ?>.00</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <form method="post" action="checkout.php">
            <div class="mt-4">
                <label for="UserName" class="block text-base font-semibold text-gray-700">Username</label>
                <input
                    type="text"
                    id="UserName"
                    name="username"
                    placeholder="Username"
                    class="mt-1 w-full rounded-md border-gray-200 shadow-sm sm:text-sm"
                    required
                />
            </div>
            <button type="submit" class="btn btn-outline-dark mt-4">Checkout</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function updateCart(cart_id, quantity) {
        window.location.href = 'update_cart.php?update_cart=1&cart_id=' + cart_id + '&quantity=' + quantity;
    }
    </script>
</body>
</html>
