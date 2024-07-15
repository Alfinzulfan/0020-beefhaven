<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Beef Haven</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/logo1.png" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
    <style>
      .card-img-top {
        object-fit: cover;
        aspect-ratio: 1.3;
      }
      .card {
        border: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
      }
      .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
      }
      .card-body {
        padding: 1.5rem;
      }
      .card-footer {
        background-color: #fff;
        border-top: none;
      }
      .price {
        font-size: 1.25rem;

        margin-top: 0.5rem;
      }

    </style>
  </head>
  <body>
    <!-- Navigation-->
    <?php include 'navbar.php'; ?>
    <!-- Header-->
    <header class="bg-dark py-5" style="background-image: url(assets/head-photo.avif); background-size: cover; background-position: center;">
      <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white">
          <h1 class="display-4 fw-bolder">Beef Haven</h1>
          <p class="lead fw-normal text-white-300 mb-0">"Beef Haven is the ultimate destination for meat lovers.".</p>
        </div>
      </div>
    </header>
    <!-- Section-->
    <section class="py-2">
      <div class="container px-4 px-lg-5 mt-5">
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
          <?php
          include 'db_connect.php';
          $sql = "SELECT product_id, name, price, img FROM products";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                  $productName = ucwords(trim($row["name"])); // Normalisasi nama produk
                  $imageSrc = $row["img"] ? $row["img"] : 'assets/default-product-image.jpg';

                  echo '<div class="col mb-5">';
                  echo '  <div class="card h-100">';
                  echo '    <img class="card-img-top" src="' . $imageSrc . '" alt="' . $row["name"] . '" />';
                  echo '    <div class="card-body p-3">';
                  echo '      <div class="text-center">';
                  echo '        <h5 class="fw-bolder">' . $productName . '</h5>';
                  echo '        <div class="price">Rp' . $row["price"] . '</div>';
                  echo '      </div>';
                  echo '    </div>';
                  echo '    <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">';
                  echo '      <div class="text-center">';
                  echo '        <form action="add_to_cart.php" method="POST">';
                  echo '          <input type="hidden" name="product_id" value="' . $row["product_id"] . '">';
                  echo '          <button type="submit" class="btn btn-outline-dark mt-auto">Add to cart</button>';
                  echo '        </form>';
                  echo '      </div>';
                  echo '    </div>';
                  echo '  </div>';
                  echo '</div>';
              }
          } else {
              echo "0 hasil";
          }
          $conn->close();
          ?>
        </div>
      </div>
    </section>
    <!-- Footer-->
    <footer class="py-4 bg-dark text-white">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
            <p class="m-0">&copy; Beef Haven 2024.</p>
          </div>
          <div class="col-md-6 text-center text-md-end">
            <a href="#" class="text-white me-2"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-white me-2"><i class="bi bi-twitter"></i></a>
            <a href="#" class="text-white me-2"><i class="bi bi-instagram"></i></a>
            <a href="#" class="text-white"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>
      </div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
  </body>
</html>
