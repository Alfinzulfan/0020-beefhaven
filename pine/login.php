<?php
session_start();
include 'db_connect.php';

$login_error = '';
$login_success = '';

// Tangani aksi logout
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    // Hapus semua data sesi
    session_unset();
    session_destroy();

    // Arahkan kembali ke halaman login
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT user_id, username, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password == $row['password']) { // Compare plain text passwords
            // Simpan user_id, username, dan role ke dalam session
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            // Mengarahkan pengguna ke index.php setelah login berhasil
            header("Location: index.php");
            exit();
        } else {
            $login_error = 'Invalid email or password.';
        }
    } else {
        $login_error = 'Invalid email or password.';
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <?php if ($login_error): ?>
        <?php include 'error_message.php'; ?>
    <?php endif; ?>

    <?php if ($login_success): ?>
        <?php include 'success_message.php'; ?>
    <?php endif; ?>
    
    <section class="relative flex flex-wrap lg:h-screen lg:items-center">
        <div class="w-full px-4 py-12 sm:px-6 sm:py-16 lg:w-1/2 lg:px-8 lg:py-24">
            <div class="mx-auto max-w-lg text-center">
                <h1 class="text-2xl font-bold sm:text-3xl">Beef Haven</h1>
                <p class="mt-4 text-gray-500">Beef Haven is the ultimate destination for beef lovers.</p>
            </div>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="mx-auto mb-0 mt-8 max-w-md space-y-4">
                <div>
                    <label for="email" class="sr-only">Email</label>
                    <div class="relative">
                        <input
                            type="email"
                            name="email"
                            class="w-full rounded-lg border-gray-200 p-3 pe-12 text-sm shadow-sm"
                            placeholder="Enter email"
                        />
                    </div>
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            name="password"
                            class="w-full rounded-lg border-gray-200 p-3 pe-12 text-sm shadow-sm"
                            placeholder="Enter password"
                        />
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500">
                        No account?
                        <a class="underline" href="register.php">Sign up</a>
                    </p>
                    <button type="submit" class="block w-full rounded-lg bg-black px-1 py-2 text-sm font-medium text-white">
                        Login
                    </button>
                </div>
            </form>
        </div>
        <div class="relative h-64 w-full sm:h-96 lg:h-full lg:w-1/2">
            <img src="assets/head-photo.avif" class="absolute inset-0 h-full w-full object-cover" />
        </div>
    </section>
</body>
</html>
