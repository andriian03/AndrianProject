<?php
    require_once '../Connect.php';
    // Insert data into products table (name, price, description, image, stock, category)

    $name = "Laptop";
    $price = 150000;
    $description = "A high-performance laptop suitable for gaming and professional work.";
    $image = "laptop.jpg";
    $stock = 10;
    $category = "Electronics";

    $sql = "INSERT INTO products (name, price, description, image, stock, category) VALUES (:name, :price, :description, :image, :stock, :category)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':price' => $price,
        ':description' => $description,
        ':image' => $image,
        ':stock' => $stock,
        ':category' => $category
    ]);

    echo "New product created successfully!";
?>