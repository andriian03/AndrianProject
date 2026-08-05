<?php
// 1. Tampilkan error PHP untuk debugging saat development
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../Connect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $name        = $_POST['name'] ?? '';
    $category    = $_POST['category'] ?? '';
    $price       = $_POST['price'] ?? 0;
    $stock       = $_POST['stock'] ?? 0;
    $description = $_POST['description'] ?? '';
    
    $image       = $_FILES['image']['name'] ?? '';
    $image_tmp   = $_FILES['image']['tmp_name'] ?? '';

    // Validate the form data
    $errors = [];
    if (empty($name)) {
        $errors['name'] = 'Nama produk harus diisi.';
    }
    if (empty($category)) {
        $errors['category'] = 'Kategori produk harus dipilih.';
    }
    // Perbaikan: gunakan is_numeric() agar nilai '0' tidak dianggap empty secara salah
    if ($price === '' || !is_numeric($price) || $price < 0) {
        $errors['price'] = 'Harga produk harus berupa angka positif.';
    }
    if ($stock === '' || !is_numeric($stock) || $stock < 0) {
        $errors['stock'] = 'Stok produk harus berupa angka positif.';
    }
    if (empty($image)) {
        $errors['image'] = 'Gambar produk harus diunggah.';
    }

    // If there are no validation errors, proceed to insert the data into the database
    if (empty($errors)) {
        $upload_dir = '../../Uploads/';
        
        // 2. Buat folder Uploads secara otomatis jika belum ada
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Remove space from the image name and replace it with underscore
        $image_clean = str_replace(' ', '_', $image);
        $image_name  = time() . '_' . basename($image_clean); // Rename the image
        $image_path  = $upload_dir . $image_name;

        if (move_uploaded_file($image_tmp, $image_path)) {
            try {
                // Prepare the SQL statement
                $sql = "INSERT INTO products (name, category, price, stock, description, image) 
                        VALUES (:name, :category, :price, :stock, :description, :image)";
                $stmt = $pdo->prepare($sql);
                
                // Bind the parameters and execute
                $stmt->execute([
                    ':name'        => $name,
                    ':category'    => $category,
                    ':price'       => $price,
                    ':stock'       => $stock,
                    ':description' => $description,
                    ':image'       => $image_name
                ]);

                // Redirect to the product list page after successful insertion
                header('Location: ../Index.php');
                exit();

            } catch (PDOException $e) {
                // Tangkap error jika query/database gagal
                die("Gagal menyimpan ke database: " . $e->getMessage());
            }
        } else {
            $errors['image'] = 'Gagal mengunggah gambar produk. Pastikan folder Uploads memiliki akses tulis.';
        }
    }

    // 3. JIKA ADA ERROR VALIDASI: Tampilkan error agar halaman tidak blank
    if (!empty($errors)) {
        echo "<h3>Gagal Menyimpan Data:</h3><ul>";
        foreach ($errors as $error) {
            echo "<li style='color:red;'>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
        echo "<a href='javascript:history.back()'>Kembali ke Form</a>";
        exit();
    }
}
?>