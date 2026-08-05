<?php
require_once '../Connect.php';
// form edit product data

// Get product ID from query parameter
$productId = $_GET['id'];
// Fetch product data from the database
$sql = "SELECT * FROM products WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    echo "Product not found!";
    exit;
}

$page_title = "Edit Produk - WebDev App";
include_once '../Template/Header.php';
?>
<main class="container my-5">
    <h1 class="mb-4">Edit Produk</h1>
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success" role="alert">
            <?= htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>
    <form action="db_action/update.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']); ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Nama Produk</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($product['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Harga</label>
            <input type="number" class="form-control" id="price" name="price" value="<?= htmlspecialchars($product['price']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="description" name="description" rows="3" required><?= htmlspecialchars($product['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Gambar</label>
            <input type="file" class="form-control" id="image" name="image">
            <?php if (!empty($product['image'])): ?>
                <img src="../uploads/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" width="100">
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">Stok</label>
            <input type="number" class="form-control" id="stock" name="stock" value="<?= htmlspecialchars($product['stock']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Kategori</label>
            <input type="text" class="form-control" id="category" name="category" value="<?= htmlspecialchars($product['category']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Produk</button>
    </form>
</main>

<?php include_once '../Template/Footer.php'; ?>