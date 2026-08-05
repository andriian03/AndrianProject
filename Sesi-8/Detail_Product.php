<?php
// Display product details by product ID
require_once 'Connect.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($product_id <= 0) {
    die('Produk tidak ditemukan.');
}

$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) {
    die('Produk tidak ditemukan.');
}

$page_title = $product['name'] . " - AADIMAS";
include_once 'template/Header.php';
?>

<div class="container my-5">
    <h1 class="mb-4"><?= htmlspecialchars($product['name']); ?></h1>
    <div class="row">
        <div class="col-md-6">
            <?php if (!empty($product['image'])): ?>
                <img src="uploads/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="img-fluid">
            <?php else: ?>
                <p>No image available.</p>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <h3>Harga: <?= htmlspecialchars($product['price']); ?></h3>
            <p><?= nl2br(htmlspecialchars($product['description'])); ?></p>
            <p>Stok: <?= htmlspecialchars($product['stock']); ?></p>
            <p>Kategori: <?= htmlspecialchars($product['category']); ?></p>
            <!-- Add to Cart Button -->
            <form action="Cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                <div class="mb-3">
                    <label for="quantity" class="form-label">Jumlah:</label>
                    <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" max="<?= htmlspecialchars($product['stock']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Tambah ke Keranjang</button>
            </form>
        </div>
    </div>
    <a href="Index.php" class="btn btn-secondary mt-3">Kembali ke Daftar Produk</a>
</div>
<?php include_once 'template/Footer.php'; ?>