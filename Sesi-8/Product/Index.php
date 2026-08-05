<?php
$page_title = "Beranda - AADIMAS"; 
include_once '../template/Header.php';

// show all products from the database in html table format
require_once '../Connect.php';

// Get search and filter parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Get all categories for filter dropdown
$sql_categories = "SELECT DISTINCT category FROM products ORDER BY category";
$stmt_categories = $pdo->query($sql_categories);
$categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

// Build SQL query with search and filter
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND name LIKE ?";
    $params[] = "%{$search}%";
}

if (!empty($category)) {
    $sql .= " AND category = ?";
    $params[] = $category;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<main class="container my-5">
    <h1 class="mb-4">Daftar Produk</h1>
    <a href="Create.php" class="btn btn-primary mb-3">Tambah Produk</a>
    
    <!-- Search and filter form -->
    <form method="GET" class="mb-3">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari produk..." value="<?= htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-4">
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['category']); ?>" <?= $cat['category'] === $category ? 'selected' : ''; ?>><?= htmlspecialchars($cat['category']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100 w-md-auto">Filter</button>
            </div>
        </div>
    </form>

    <!-- Tabel Produk Teratur -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-dark text-nowrap">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th style="min-width: 250px;">Deskripsi</th>
                    <th>Gambar</th>
                    <th>Stok</th>
                    <th>Kategori</th>
                    <th style="min-width: 120px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1; // Inisialisasi nomor urut dari angka 1
                foreach ($products as $product): 
                ?>
                    <tr>
                        <td><?= $no++; ?></td> <!-- Menampilkan nomor urut incremental -->
                        <td class="fw-bold"><?= htmlspecialchars($product['name']); ?></td>
                        <td>Rp <?= number_format($product['price'], 0, ',', '.'); ?></td>
                        <td class="text-start"><?= htmlspecialchars($product['description']); ?></td>
                        <td>
                            <img src="../uploads/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="img-thumbnail" style="max-width: 80px; height: auto;">
                        </td>
                        <td><?= htmlspecialchars($product['stock']); ?></td>
                        <td><span class="badge bg-secondary"><?= htmlspecialchars($product['category']); ?></span></td>
                        <td>
                            <div class="d-grid gap-1 d-md-block">
                                <a href="Edit.php?id=<?= htmlspecialchars($product['id']); ?>" class="btn btn-sm btn-warning">Edit</a>
                                <form action="db_action/Delete.php" method="POST" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']); ?>">
                                    <button type="submit" class="btn btn-sm btn-danger mt-1 mt-md-0">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Tidak ada produk ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
<?php include_once '../template/Footer.php'; ?>