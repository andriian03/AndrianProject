<?php
session_start();
$page_title = "Checkout - AADIMAS";
include_once 'template/Header.php';
?> 
<div class="container my-5" style="max-width: 600px;margin: auto;">
    <h1 class="mb-4">Checkout</h1>
    <?php if (empty($_SESSION['cart'])): ?>
        <p>Keranjang belanja Anda kosong. Silakan tambahkan produk terlebih dahulu.</p>
        <a href="Index.php" class="btn btn-primary">Kembali ke Toko</a>
    <?php else: ?>
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
                <?php
                $grand_total = 0;
                foreach ($_SESSION['cart'] as $product_id => $item):
                    $total_price = $item['price'] * $item['quantity'];
                    $grand_total += $total_price;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']); ?></td>
                        <td>Rp <?= number_format($item['price'], 0, ',', '.'); ?></td>
                        <td><?= $item['quantity']; ?></td>
                        <td>Rp <?= number_format($total_price, 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Grand Total:</th>
                    <th>Rp <?= number_format($grand_total, 0, ',', '.'); ?></th>
                </tr>
            </tfoot>
        </table>

        <!-- Checkout Form -->
        <form action="Process_checkout.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" placeholder="Nama Lengkap" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" placeholder="example@example.com" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Nomor Telepon</label>
                <input type="number" placeholder="08123456789" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Alamat Pengiriman</label>
                <textarea class="form-control" id="address" name="address" rows="3" required placeholder="Alamat Pengiriman"></textarea>
            </div>
            <div class="mb-3">
                <label for="payment_method" class="form-label">Metode Pembayaran</label>
                <select class="form-select" id="payment_method" name="payment_method" required>
                    <option value="">-- Pilih Metode Pembayaran --</option>
                    <option value="bank_transfer">Transfer Bank</option>
                    <option value="cash_on_delivery">Cash on Delivery (COD)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Proses Checkout</button>
        </form>
    <?php endif; ?>
</div>
<?php include_once 'template/Footer.php'; ?>