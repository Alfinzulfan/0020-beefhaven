<?php
include 'db_connect.php'; // Menghubungkan ke database

$checkout_success = false; // Variabel untuk melacak status sukses checkout

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username']; // Ambil username dari form
    $sql = "SELECT * FROM cart";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $product_name = $row['product_name'];
            $product_price = $row['product_price'];
            $quantity = $row['quantity'];
            $subtotal = $row['subtotal'];
            $checkout_date = date('Y-m-d H:i:s'); // Menambahkan tanggal checkout saat ini

            $sql_checkout = "INSERT INTO checkout (product_name, product_price, quantity, subtotal, checkout_date, username) VALUES ('$product_name', $product_price, $quantity, $subtotal, '$checkout_date', '$username')";
            if ($conn->query($sql_checkout) !== TRUE) {
                echo "Error: " . $sql_checkout . "<br>" . $conn->error;
            }
        }
        // Menghapus semua item di cart setelah proses checkout
        $sql_clear_cart = "DELETE FROM cart";
        if ($conn->query($sql_clear_cart) !== TRUE) {
            echo "Error: " . $sql_clear_cart . "<br>" . $conn->error;
        } else {
            $checkout_success = true; // Set variabel sukses menjadi true
        }
    } else {
        echo "Cart is empty.";
    }
}

// Mengambil data dari tabel checkout
$sql_latest_transaction = "
    SELECT *
    FROM checkout
    WHERE (checkout_date, username) IN (
        SELECT checkout_date, username
        FROM (
            SELECT DISTINCT checkout_date, username
            FROM checkout
            ORDER BY checkout_date DESC
            LIMIT 1
        ) AS temp
    )
    ORDER BY checkout_date, username, product_name";

$result_latest_transaction = $conn->query($sql_latest_transaction);

$conn->close(); // Menutup koneksi setelah semua operasi selesai
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body>
    <?php
    include 'navbar.php';
    ?>

    <?php if ($checkout_success): ?>
        <?php include 'success_message.php'; ?>
    <?php endif; ?>

    <div class="container mt-5">
        <h2 class="text-start display-6 fw-bolder"> Checkout</h2>
        <p>Thank you for your order</p>

        <?php
        $lastUsername = "";
        $lastDate = "";
        $currentTotal = 0;

        if ($result_latest_transaction->num_rows > 0) {
            while($row = $result_latest_transaction->fetch_assoc()) {
                if ($lastDate != $row['checkout_date'] || $lastUsername != $row['username']) {
                    if ($lastDate != "" && $lastUsername != "") {
                        echo "<tr>";
                        echo "<td colspan='5' class='whitespace-nowrap px-4 py-2 text-right font-bold'>Total</td>";
                        echo "<td class='whitespace-nowrap px-4 py-2 text-gray-700 font-bold'>Rp" . number_format($currentTotal, 2) . "</td>";
                        echo "</tr>";
                        echo "</tbody>";
                        echo "</table>";
                        echo "</div>";
                    }
                    $currentTotal = 0; // Reset total untuk transaksi baru
                    $lastDate = $row['checkout_date'];
                    $lastUsername = $row['username'];

                    echo "<div class='overflow-x-auto rounded-lg border border-gray-400 mt-5'>";
                    echo "<h4 class='pl-5 py-2 mt-3 font-semibold'>Transaction Date: " . $lastDate . "</h4>";
                    echo "<h4 class='pl-5 mb-3 font-semibold'>Username: " . $lastUsername . "</h4>";
                    echo "<table class='min-w-full divide-y-4 divide-gray-400 bg-white text-base'>";
                    echo "<thead class='ltr:text-left rtl:text-right'>";
                    echo "<tr>";
                    echo "<th class='whitespace-nowrap px-4 py-2 text-lg font-bold text-gray-900'>Name Product</th>";
                    echo "<th class='whitespace-nowrap px-4 py-2 font-bold text-gray-900'>Price</th>";
                    echo "<th class='whitespace-nowrap px-4 py-2 font-bold text-gray-900'>Qty</th>";
                    echo "<th class='whitespace-nowrap px-4 py-2 font-bold text-gray-900'>Subtotal</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody class='divide-y divide-gray-200'>";
                }

                $currentTotal += $row['subtotal']; // Tambahkan subtotal ke total saat ini

                echo "<tr>";
                echo "<td class='whitespace-nowrap px-4 py-2 font-semibold text-gray-700'>" . $row['product_name'] . "</td>";
                echo "<td class='whitespace-nowrap px-4 py-2 text-gray-700'>Rp" . number_format($row['product_price'], 2) . "</td>";
                echo "<td class='whitespace-nowrap px-4 py-2 text-gray-700'>" . $row['quantity'] . "</td>";
                echo "<td class='whitespace-nowrap px-4 py-2 text-gray-700'>Rp" . number_format($row['subtotal'], 2) . "</td>";
                echo "</tr>";
            }
            echo "<tr>";
            echo "<td colspan='5' class='whitespace-nowrap px-3 py-2 text-right font-bold'>Total</td>";
            echo "<td class='whitespace-nowrap pr-1 py-2 text-gray-700 font-bold'>Rp" . number_format($currentTotal, 2) . "</td>";
            echo "</tr>";
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        } else {
            echo "<p>No transactions found.</p>";
        }
        ?>
                    
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
