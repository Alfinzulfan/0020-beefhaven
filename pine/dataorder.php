<?php
include 'db_connect.php'; // Menghubungkan ke database

// Pengaturan pagination
$transactions_per_page = 1; // Jumlah transaksi per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $transactions_per_page;

// Mendapatkan total jumlah transaksi
$sql_total_transactions = "SELECT COUNT(DISTINCT checkout_date, username) AS total_transactions FROM checkout";
$result_total_transactions = $conn->query($sql_total_transactions);
$total_transactions_row = $result_total_transactions->fetch_assoc();
$total_transactions = $total_transactions_row['total_transactions'];
$total_pages = ceil($total_transactions / $transactions_per_page);

// Mendapatkan data transaksi untuk halaman saat ini
$sql_checkout_data = "
    SELECT * 
    FROM checkout
    WHERE (checkout_date, username) IN (
        SELECT checkout_date, username
        FROM (
            SELECT DISTINCT checkout_date, username
            FROM checkout
            ORDER BY checkout_date DESC
            LIMIT $transactions_per_page OFFSET $offset
        ) AS temp
    )
    ORDER BY checkout_date, username, product_name";

$result_checkout_data = $conn->query($sql_checkout_data);

$conn->close(); // Menutup koneksi setelah semua operasi selesai
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - Checkout Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="flex">
        <?php include 'sidemenu.php'; ?>
        <div class="flex-grow">
            <div class="container mt-5">
                <h2 class="text-start display-6 fw-bolder">Admin - Checkout Data</h2>
                <p>All transactions are displayed below:</p>

                <?php
                $lastUsername = "";
                $lastDate = "";
                $currentTotal = 0;

                if ($result_checkout_data->num_rows > 0) {
                    while($row = $result_checkout_data->fetch_assoc()) {
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
                            echo "<h4 class='pl-5 py-2 mt-2 text-lg font-semibold'>Transaction Date: " . $lastDate . "</h4>";
                            echo "<h4 class='pl-5 mb-3 text-lg font-semibold'>Username: " . $lastUsername . "</h4>";
                            echo "<table class='min-w-full divide-y-4 divide-gray-400 bg-white text-base'>";
                            echo "<thead class='ltr:text-left rtl:text-right'>";
                            echo "<tr>";
                            echo "<th class='whitespace-nowrap px-4 py-2 text-lg font-bold text-gray-900'>Name Product</th>";
                            echo "<th class='whitespace-nowrap px-4 py-2 font-bold text-gray-900'>Price</th>";
                            echo "<th class='whitespace-nowrap px-4 py-2 font-bold text-gray-900'>Qty</th>";
                            echo "<th class='whitespace-nowrap px-4 py-2 font-bold text-gray-900'>Subtotal</th>";
                            echo "<th class='whitespace-nowrap px-4 py-2 font-bold text-gray-900'>Date</th>";
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
                        echo "<td class='whitespace-nowrap px-4 py-2 text-gray-700'>" . $row['checkout_date'] . "</td>";
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

                <!-- Pagination controls -->
                <nav class="flex justify-center mt-4">
                    <ul class="pagination" style="display: flex; list-style: none; padding: 0;">
                        <?php if ($page > 1): ?>
                        <li class="page-item" style="margin: 0 5px;">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous" style="display: block; padding: 0.5rem 0.75rem; color: var(--bs-black); text-decoration: none; background-color: var(--bs-white); border: 1px solid var(--bs-gray-300); border-radius: 0.25rem;">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>" style="margin: 0 5px;">
                            <a class="page-link" href="?page=<?php echo $i; ?>" style="display: block; padding: 0.5rem 0.75rem; color: <?php echo ($i == $page) ? 'var(--bs-white)' : 'var(--bs-black)'; ?>; text-decoration: none; background-color: <?php echo ($i == $page) ? 'var(--bs-black)' : 'var(--bs-white)'; ?>; border: 1px solid <?php echo ($i == $page) ? 'var(--bs-black)' : 'var(--bs-gray-300)'; ?>; border-radius: 0.25rem;">
                                <?php echo $i; ?>
                            </a>
                        </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                        <li class="page-item" style="margin: 0 5px;">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next" style="display: block; padding: 0.5rem 0.75rem; color: var(--bs-black); text-decoration: none; background-color: var(--bs-white); border: 1px solid var(--bs-gray-300); border-radius: 0.25rem;">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</body>
</html>
