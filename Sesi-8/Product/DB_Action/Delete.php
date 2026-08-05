<?php
require_once '../../Connect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $id = $_POST['id'];
    // Get the old image name from the database
    $sql = "SELECT image FROM products WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $oldImage = $stmt->fetchColumn();
    // Delete the old image file if it exists
    if ($oldImage && file_exists('../../uploads/' . $oldImage)) {
        unlink('../../Uploads/' . $oldImage);
    }

    // Delete the product from the database
    $sql = "DELETE FROM products WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    // Redirect to the product list page after deletion
    header('Location: ../Index.php');
    exit();
}