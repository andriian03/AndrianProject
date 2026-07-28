<?php
session_start();

// Mencegah akses langsung ke file ini jika bukan via POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

$errors = [];

// 1. Validasi Nama Produk
if (empty($_POST["nama_produk"])) {
    $errors[] = "Nama produk wajib diisi.";
} else {
    $nama_produk = htmlspecialchars(trim($_POST["nama_produk"]));
    if (strlen($nama_produk) < 3) {
        $errors[] = "Nama produk minimal terdiri dari 3 karakter.";
    }
}

// 2. Validasi Harga
if (empty($_POST["harga"])) {
    $errors[] = "Harga produk wajib diisi.";
} else {
    $harga = trim($_POST["harga"]);
    if (!is_numeric($harga) || $harga <= 0) {
        $errors[] = "Harga harus berupa angka dan lebih besar dari 0.";
    }
}

// 3. Validasi Stok
if ($_POST["stock"] === "") {
    $errors[] = "Stok produk wajib diisi.";
} else {
    $stock = trim($_POST["stock"]);
    if (!filter_var($stock, FILTER_VALIDATE_INT, ["options" => ["min_range" => 0]])) {
        $errors[] = "Stok harus berupa angka bulat dan tidak boleh negatif.";
    }
}

// 4. Validasi Deskripsi
if (empty($_POST["deskripsi"])) {
    $errors[] = "Deskripsi produk wajib diisi.";
} else {
    $deskripsi = htmlspecialchars(trim($_POST["deskripsi"]));
}

// Cek apakah ada error
if (!empty($errors)) {
    // Kirim pesan error dan data lama kembali ke frontend
    $_SESSION['errors'] = $errors;
    $_SESSION['old_input'] = $_POST;
} else {
    // JIKA VALIDASI SUKSES:
    // Di sini Anda bisa menambahkan logika simpan ke Database (misal: query INSERT INTO)
    
    $_SESSION['success'] = "Produk <strong>" . $nama_produk . "</strong> berhasil disimpan!";
}

// Redirect kembali ke halaman utama (Frontend)
header("Location: index.php");
exit();