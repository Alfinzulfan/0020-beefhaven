<?php
session_start();
include 'db_connect.php';

$success_message = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $img = '';

    // Handle file upload
    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $upload_dir = 'uploads/';
        // Make sure the uploads directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $upload_file = $upload_dir . basename($_FILES['img']['name']);
        
        if (move_uploaded_file($_FILES['img']['tmp_name'], $upload_file)) {
            $img = $upload_file;  // Save the path to the uploaded file
        } else {
            echo "Error uploading the file.";
        }
    }

    $sql = "INSERT INTO products (name, price, img) VALUES ('$name', '$price', '$img')";

    if ($conn->query($sql) === TRUE) {
        $success_message = 'New product added successfully';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="flex">
  <?php include 'sidemenu.php'; ?>

  <section class="bg-gray-100 flex-grow">
    <div class="mx-auto max-w-screen-xl px-4 py-16 sm:px-6 lg:px-8">
      <div class="rounded-lg bg-white p-8 shadow-sm lg:col-span-3 lg:p-12">
        <?php if ($success_message): ?>
          <?php include 'success_message.php'; ?>
        <?php endif; ?>
        <form action="admin.php" method="post" enctype="multipart/form-data" class="space-y-4" onsubmit="return validateForm()">
          <div>
            <label class="sr-only" for="name">Product Name</label>
            <input
              class="w-full rounded-lg border-2 border-gray-400 p-2 text-sm"
              placeholder="Product Name"
              type="text"
              id="name"
              name="name"
              required
            />
          </div>

          <div>
            <label class="sr-only" for="price">Price</label>
            <input
              class="w-full rounded-lg border-2 border-gray-400 p-2 text-sm"
              placeholder="Price"
              type="text"
              id="price"
              name="price"
              required
            />
          </div>

          <div>
            <label class="sr-only" for="img">Image</label>
            <input
              class="w-full rounded-lg border-2 border-gray-400 p-3 text-sm"
              type="file"
              id="img"
              name="img"
              required
            />
          </div>

          <div class="mt-4">
            <button
              type="submit"
              class="btn btn-outline-dark inline-block w-full rounded-lg bg-black px-2 py-2 font-sm text-white lg:w-100"
            >
              Add Product
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function validateForm() {
  var name = document.getElementById('name').value;
  var price = document.getElementById('price').value;
  var img = document.getElementById('img').value;

  if (name == "" || price == "" || img == "") {
    alert("All fields must be filled out");
    return false;
  }
  return true;
}
</script>
</body>
</html>
