<?php
require_once '../../Connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];

    // Validate the form data
    $errors = [];
    if (empty($name)) {
        $errors['name'] = 'Nama produk harus diisi.';
    }
    if (empty($category)) {
        $errors['category'] = 'Kategori produk harus dipilih.';
    }
    if (empty($price) || !is_numeric($price) || $price < 0) {
        $errors['price'] = 'Harga produk harus berupa angka positif.';
    }
    if (empty($stock) || !is_numeric($stock) || $stock < 0) {
        $errors['stock'] = 'Stok produk harus berupa angka positif.';
    }
    if (!empty($errors)) {
        // Handle the errors, e.g., redirect back to the edit form with error messages
        // You can store the errors in the session or display them directly
        session_start();
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $_POST;
        header('Location: ../Edit.php?id=' . $id);
        exit();
    }

    // Check if a new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];

        // Move the uploaded image to the desired directory
        $upload_dir = '../../uploads/';
        // remove space from the image name and replace it with underscore
        $image = str_replace(' ', '_', $image);
        $image = time() . '_' . basename($image); // Rename the image to avoid conflicts
        $image_path = $upload_dir . $image;
        move_uploaded_file($image_tmp, $image_path);

        // delete the old image if it exists
        $sql = "SELECT image FROM products WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $oldImage = $stmt->fetchColumn();
        if ($oldImage && file_exists($upload_dir . $oldImage)) {
            unlink($upload_dir . $oldImage);
        }

        // Update the product with the new image
        $sql = "UPDATE products SET name = :name, category = :category, price = :price, stock = :stock, description = :description, image = :image WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':category' => $category,
            ':price' => $price,
            ':stock' => $stock,
            ':description' => $description,
            ':image' => $image,
            ':id' => $id
        ]);
    } else {
        // Update the product without changing the image
        $sql = "UPDATE products SET name = :name, category = :category, price = :price, stock = :stock, description = :description WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':category' => $category,
            ':price' => $price,
            ':stock' => $stock,
            ':description' => $description,
            ':id' => $id
        ]);
    }

    // Redirect to the product list page after successful update
    header('Location: ../Index.php');
    exit();
}