<?php

require_once 'Connect.php';

// add to cart save to session action not database
session_start();
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle different cart actions
    $action = isset($_POST['action']) ? $_POST['action'] : 'add';
    
    if ($action === 'add') {
        $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

        // Validate product ID and quantity
        if ($product_id <= 0 || $quantity <= 0) {
            die('Invalid product or quantity.');
        }

        // Fetch product details from the database
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            die('Product not found.');
        }

        // Initialize cart in session if not already set
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Add or update product in the cart
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'image' => $product['image']
            ];
        }
    } elseif ($action === 'update') {
        $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

        // Validate
        if ($product_id <= 0 || $quantity <= 0) {
            die('Invalid product or quantity.');
        }

        // Update quantity
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        }
    } elseif ($action === 'delete') {
        $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

        // Remove product from cart
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    // Redirect to cart page after action
    header('Location: cart.php');
    exit();
}
$page_title = "Keranjang Belanja - AADIMAS";
include_once 'template/Header.php';
?>

<div class="container my-5">
    <h1 class="mb-4">Keranjang Belanja</h1>
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Keranjang belanja Anda kosong.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $grand_total = 0; ?>
                <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                    <?php $total = $item['price'] * $item['quantity']; ?>
                    <?php $grand_total += $total; ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']); ?></td>
                        <td>Rp <?= number_format($item['price'], 0, ',', '.'); ?></td>
                        <td>
                            <form method="POST" class="d-flex gap-2" style="display: inline-flex;">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="product_id" value="<?= $product_id; ?>">
                                <input type="number" name="quantity" value="<?= htmlspecialchars($item['quantity']); ?>" min="1" class="form-control" style="width: 70px;">
                                <button type="submit" class="btn btn-sm btn-info">Update</button>
                            </form>
                        </td>
                        <td>Rp <?= number_format($total, 0, ',', '.'); ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="product_id" value="<?= $product_id; ?>">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total Keseluruhan</th>
                    <th>Rp <?= number_format($grand_total, 0, ',', '.'); ?></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    <?php endif; ?>
    <div class="mt-3 d-flex justify-content-between">
        <a href="Index.php" class="btn btn-secondary">Lanjutkan Belanja</a>
        <?php if (!empty($_SESSION['cart'])): ?>
            <a href="Checkout.php" class="btn btn-success">Checkout</a>
        <?php endif; ?>
    </div>
    <!-- <a href="Index.php" class="btn btn-secondary mt-3">Lanjutkan Belanja</a> -->
</div>
<?php include_once 'template/Footer.php'; ?>