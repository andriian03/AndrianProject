<?php
require_once 'Connect.php';

// get the order ID from the query parameter
if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['order_id'])) {
    $order_id = (int)$_GET['order_id'];

    // Fetch order details from the database
    $sql = "SELECT * FROM orders WHERE id = :order_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':order_id' => $order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        die('Order not found.');
    }

    // Fetch order items from the database
    $sql_items = "SELECT oi.*, p.name, p.price FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :order_id";
    $stmt_items = $pdo->prepare($sql_items);
    $stmt_items->execute([':order_id' => $order_id]);
    $order_items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
} else {
    die('Invalid request.');
}

$page_title = "Order Success - AADIMAS";
include_once 'template/Header.php';

$text = "Halo Admin, saya ingin mengkonfirmasi pesanan dengan Order ID " . $order['id'];
$walink = "https://wa.me/6281234567890?text=" . urlencode($text);
?>
<div class="container my-5" style="max-width: 600px;margin: auto;">
    <h1 class="mb-4">Order Berhasil!</h1>
    <p>Terima kasih telah melakukan pembelian. Berikut adalah detail pesanan Anda:</p>
    <ul>
        <li><strong>Order ID:</strong> <?= htmlspecialchars($order['id']); ?></li>
        <li><strong>Nama:</strong> <?= htmlspecialchars($order['name']); ?></li>
        <li><strong>Email:</strong> <?= htmlspecialchars($order['email']); ?></li>
        <li><strong>Nomor Telepon:</strong> <?= htmlspecialchars($order['phone']); ?></li>
        <li><strong>Alamat:</strong> <?= htmlspecialchars($order['address']); ?></li>
        <li><strong>Total Harga:</strong> Rp <?= number_format($order['total_amount'], 0, ',', '.'); ?></li>
        <li><strong>Tanggal Pemesanan:</strong> <?= htmlspecialchars($order['created_at']); ?></li>
        <li><strong>Metode Pembayaran:</strong> <?= htmlspecialchars(str_replace('_', ' ', $order['payment_method'])); ?></li>
        <li><strong>Status:</strong> <?= htmlspecialchars(str_replace('_', ' ', $order['status'])); ?></li>
    </ul>
    <h3 class="mt-4">Detail Produk:</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($order_items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']); ?></td>
                    <td>Rp <?= number_format($item['price'], 0, ',', '.'); ?></td>
                    <td><?= htmlspecialchars($item['quantity']); ?></td>
                    <td>Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="d-flex justify-content-between mt-4">
        <a href="Index.php" class="btn btn-primary">Kembali ke Toko</a>
        <a href="<?= $walink; ?>" class="btn btn-success">Konfirmasi Pesanan</a>
    </div>
</div>
<?php include_once 'template/Footer.php'; ?>