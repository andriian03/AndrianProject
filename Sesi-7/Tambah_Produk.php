<?php
session_start();

// Ambil pesan/error atau data input lama dari session jika ada
$errors = $_SESSION['errors'] ?? [];
$success_message = $_SESSION['success'] ?? '';
$old_input = $_SESSION['old_input'] ?? [];

// Hapus session flash message agar tidak tampil lagi saat page di-refresh
unset($_SESSION['errors'], $_SESSION['success'], $_SESSION['old_input']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Produk</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">Form Input Produk</h4>
                </div>
                <div class="card-body p-4">

                    <!-- Success Alert -->
                    <?php if (!empty($success_message)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= $success_message ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Error Alert -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

<!-- Form dihubungkan langsung ke Proses_Produk.php -->
<form action="Proses_Produk.php" method="POST" novalidate>
                        
                        <!-- Nama Produk -->
                        <div class="mb-3">
                            <label for="nama_produk" class="form-label font-weight-bold">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nama_produk" 
                                   name="nama_produk" 
                                   placeholder="Contoh: Kaos Polos Cotton Combed"
                                   value="<?= htmlspecialchars($old_input['nama_produk'] ?? '') ?>">
                        </div>

                        <!-- Kategori Produk -->
                        <div class="mb-3">
                            <label for="kategori" class="form-label font-weight-bold">Kategori Produk <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="kategori" 
                                   name="kategori" 
                                   placeholder="Contoh: Pakaian / Aksesoris"
                                   value="<?= htmlspecialchars($old_input['kategori'] ?? '') ?>">
                        </div>
                        <!-- Harga & Stock -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="harga" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control" 
                                       id="harga" 
                                       name="harga" 
                                       placeholder="50000"
                                       value="<?= htmlspecialchars($old_input['harga'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control" 
                                       id="stock" 
                                       name="stock" 
                                       placeholder="100"
                                       value="<?= htmlspecialchars($old_input['stock'] ?? '') ?>">
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Produk <span class="text-danger">*</span></label>
                            <textarea class="form-control" 
                                      id="deskripsi" 
                                      name="deskripsi" 
                                      rows="4" 
                                      placeholder="Tuliskan spesifikasi atau keunggulan produk..."><?= htmlspecialchars($old_input['deskripsi'] ?? '') ?></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Simpan Produk</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>