<?php
$page_title = "Beranda - AADIMAS"; 
include_once 'template/Header.php';

// show all products from the database in html table format
require_once 'Connect.php';

// Pagination settings
$items_per_page = 12;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) {
    $current_page = 1;
}

// Get search and filter parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Get all categories for filter dropdown
$sql_categories = "SELECT DISTINCT category FROM products ORDER BY category";
$stmt_categories = $pdo->query($sql_categories);
$categories = $stmt_categories->fetchAll(PDO::FETCH_ASSOC);

// Build SQL query with search and filter for counting total records
$sql_count = "SELECT COUNT(*) as total FROM products WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql_count .= " AND name LIKE ?";
    $params[] = "%{$search}%";
}

if (!empty($category)) {
    $sql_count .= " AND category = ?";
    $params[] = $category;
}

$stmt_count = $pdo->prepare($sql_count);
$stmt_count->execute($params);
$total_records = $stmt_count->fetch(PDO::FETCH_ASSOC)['total'];
$total_pages = ceil($total_records / $items_per_page);

// Ensure current page is within valid range
if ($current_page > $total_pages && $total_pages > 0) {
    $current_page = $total_pages;
}

// Calculate offset
$offset = ($current_page - 1) * $items_per_page;

// Build SQL query with search and filter and pagination
$sql = "SELECT * FROM products WHERE 1=1";

// Reset params for new query
$params = [];
if (!empty($search)) {
    $sql .= " AND name LIKE ?";
    $params[] = "%{$search}%";
}

if (!empty($category)) {
    $sql .= " AND category = ?";
    $params[] = $category;
}

$sql .= " LIMIT " . (int)$items_per_page . " OFFSET " . (int)$offset;

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container my-5">
    <h1 class="mb-4">Daftar Produk</h1>
    <form method="GET" class="mb-3 d-flex align-items-center">
        <input type="text" name="search" class="form-control me-2" placeholder="Cari produk..." value="<?= htmlspecialchars($search); ?>">
        <select name="category" class="form-select me-2">
            <option value="">Semua Kategori</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat['category']); ?>" <?= $cat['category'] === $category ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($cat['category']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>
    <div class="row">
        <?php if (empty($products)): ?>
            <p>Tidak ada produk yang ditemukan.</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="col-1 col-lg-2 col-md-4 mb-4">
                    <a href="Detail_product.php?id=<?= $product['id']; ?>" class="card h-100">
                        <?php if (!empty($product['image']) && file_exists('uploads/' . $product['image'])): ?>
                            <img src="uploads/<?= htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']); ?>">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/150" class="card-img-top" alt="<?= htmlspecialchars($product['name']); ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text"><strong>Harga:</strong> Rp <?= number_format($product['price'], 0, ',', '.'); ?></p>
                            <p class="card-text"><strong>Kategori:</strong> <?= htmlspecialchars($product['category']); ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Pagination Controls -->
    <?php if ($total_pages > 1): ?>
    <nav aria-label="Pagination Navigation" class="mt-5">
        <ul class="pagination justify-content-center">
            <!-- Previous Page -->
            <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $current_page - 1; ?>&search=<?= urlencode($search); ?>&category=<?= urlencode($category); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link">&laquo;</span>
                </li>
            <?php endif; ?>

            <!-- Page Numbers -->
            <?php
            $start_page = max(1, $current_page - 2);
            $end_page = min($total_pages, $current_page + 2);
            
            if ($start_page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=1&search=<?= urlencode($search); ?>&category=<?= urlencode($category); ?>">1</a>
                </li>
                <?php if ($start_page > 2): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif;
            endif;

            for ($i = $start_page; $i <= $end_page; $i++): ?>
                <?php if ($i == $current_page): ?>
                    <li class="page-item active">
                        <span class="page-link"><?= $i; ?></span>
                    </li>
                <?php else: ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $i; ?>&search=<?= urlencode($search); ?>&category=<?= urlencode($category); ?>"><?= $i; ?></a>
                    </li>
                <?php endif;
            endfor;

            if ($end_page < $total_pages): ?>
                <?php if ($end_page < $total_pages - 1): ?>
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                <?php endif; ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $total_pages; ?>&search=<?= urlencode($search); ?>&category=<?= urlencode($category); ?>"><?= $total_pages; ?></a>
                </li>
            <?php endif; ?>

            <!-- Next Page -->
            <?php if ($current_page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $current_page + 1; ?>&search=<?= urlencode($search); ?>&category=<?= urlencode($category); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link">&raquo;</span>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="text-center text-muted mt-3">
        Halaman <?= $current_page; ?> dari <?= $total_pages; ?> | Total: <?= $total_records; ?> produk
    </div>
    <?php endif; ?>
</div>
<?php include_once 'template/Footer.php'; ?>