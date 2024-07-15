<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
<?php 
include 'navbar.php';
include 'db_connect.php';

$register_error = '';
$register_success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Ambil peran dari form

    // Mengecek apakah email sudah ada
    $checkEmailQuery = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $register_error = 'Email sudah ada. Silakan gunakan email lain.';
    } else {
        // Menyimpan pengguna baru dengan peran yang dipilih
        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $password, $role);

        if ($stmt->execute()) {
            $register_success = 'Registrasi berhasil!';
        } else {
            $register_error = 'Error: ' . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<?php if ($register_error): ?>
    <?php include 'error_message.php'; ?>
<?php endif; ?>

<?php if ($register_success): ?>
    <?php include 'success_message.php'; ?>
<?php endif; ?>

<section class="relative flex flex-wrap lg:h-screen lg:items-center">
    <div class="w-full px-4 py-12 sm:px-6 sm:py-16 lg:w-2/3 lg:px-8 lg:py-24 mx-auto">
        <div class="mx-auto max-w-lg text-center">
            <h1 class="text-2xl font-bold sm:text-3xl">Register</h1>
            <p class="mt-4 text-gray-500">Join Beef Haven and explore the ultimate destination for beef lovers.</p>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="mx-auto mb-0 mt-8 max-w-md space-y-4">
            <div>
                <label for="username" class="sr-only">Username</label>
                <div class="relative">
                    <input
                        type="text"
                        name="username"
                        class="w-full rounded-lg border-gray-200 p-3 pe-12 text-sm shadow-sm"
                        placeholder="Enter username"
                        required
                    />
                </div>
            </div>
            <div>
                <label for="email" class="sr-only">Email</label>
                <div class="relative">
                    <input
                        type="email"
                        name="email"
                        class="w-full rounded-lg border-gray-200 p-3 pe-12 text-sm shadow-sm"
                        placeholder="Enter email"
                        required
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
                        required
                    />
                </div>
            </div>
            <div>
                <label for="role" class="sr-only">Role</label>
                <div class="relative">
                    <select name="role" class="w-full rounded-lg border-gray-200 p-3 pe-12 text-sm shadow-sm" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
            <button
                type="submit"
                class="block w-full rounded-lg bg-black px-5 py-3 text-sm font-medium text-white"
            >
                Sign up
            </button>
            <p class="text-center text-sm text-gray-500">
                Already have an account?
                <a class="underline" href="login.php">Login</a>
            </p>
        </form>
    </div>
</section>
</body>
</html>
