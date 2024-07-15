<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/styles.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .sidebar {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      height: 100vh;
    }
    .menu-items {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 1rem;
    }
    .menu-items a, .menu-items button {
      text-align: center;
      width: 100%;
    }
  </style>
</head>
<body>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Ambil informasi pengguna dari sesi
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : 'Guest';
?>

<div class="sidebar border-e bg-white">
  <div class="px-4 py-6">
    <a href="index.php" class="block h-10 w-32 mx-auto mb-6">
      <img src="assets/logo1.png" alt="Logo" class="h-full w-full object-contain">
    </a>

    <div class="menu-items">
      <a href="admin.php" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 no-underline">
        Add Product
      </a>
      <a href="dataorder.php" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 no-underline">
        Data Order
      </a>
      <form action="login.php" method="post" class="w-full">
        <input type="hidden" name="logout" value="1">
        <button type="submit" class="w-full rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700">
          Logout
        </button>
      </form>
    </div>
  </div>

  <div class="sticky bottom-0 border-t border-gray-100 bg-white w-full p-4">
    <a href="#" class="flex items-center gap-2 hover:bg-gray-50 p-2 rounded-lg">
      <img
        alt="Avatar"
        src="assets/default-avatar.png"
        class="h-10 w-10 rounded-full object-cover"
      />
      <div>
        <p class="text-xs font-medium text-center"><?php echo htmlspecialchars($username); ?></p>
        <p class="text-xs text-gray-500 text-center"><?php echo htmlspecialchars($role); ?></p>
      </div>
    </a>
  </div>
</div>

</body>
</html>
