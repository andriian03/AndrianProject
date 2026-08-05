<?php
session_start();

// 1. Konfigurasi Koneksi Database
$host     = "localhost";
$username = "root";
$password = ""; // Kosongkan jika menggunakan default XAMPP
$database = "Project_AndrianDB";

$conn = new mysqli($host, $username, $password, $database);

// Cek Koneksi Database
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Pastikan request datang dari method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: Tambah_Produk.php");
    exit();
}

$errors = [];

// Validasi Nama Produk
if (empty($_POST['nama_produk'])) {
    $errors[] = "Nama produk wajib diisi.";
} else {
    $nama_produk = htmlspecialchars(trim($_POST['nama_produk']));
    if (strlen($nama_produk) < 3) {
        $errors[] = "Nama produk minimal terdiri dari 3 karakter.";
    }
}

// Kategori
$kategori = trim($_POST['kategori'] ?? '');
if (empty($kategori)) {
    $errors[] = "Kategori produk wajib diisi.";
} else {
    $kategori = htmlspecialchars($kategori);
}

// Validasi Harga
if (!isset($_POST['harga']) || $_POST['harga'] === '') {
    $errors[] = "Harga produk wajib diisi.";
} else {
    $harga = trim($_POST['harga']);
    if (!is_numeric($harga) || $harga <= 0) {
        $errors[] = "Harga harus berupa angka dan lebih besar dari 0.";
    }
}

// Validasi Stok
if (!isset($_POST['stock']) || $_POST['stock'] === '') {
    $errors[] = "Stok produk wajib diisi.";
} else {
    $stock = trim($_POST['stock']);
    if (!filter_var($stock, FILTER_VALIDATE_INT, ["options" => ["min_range" => 0]])) {
        $errors[] = "Stok harus berupa angka bulat dan tidak boleh negatif.";
    }
}

// Validasi Deskripsi
if (empty($_POST['deskripsi'])) {
    $errors[] = "Deskripsi produk wajib diisi.";
} else {
    $deskripsi = htmlspecialchars(trim($_POST['deskripsi']));
}

// Jika terjadi error validasi, kembalikan ke Tambah_Produk.php
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old_input'] = $_POST; // Simpan input lama agar user tidak ketik ulang
    header("Location: Tambah_Produk.php");
    exit();
}

// 4. Query Insert ke Database menggunakan Prepared Statement (Aman dari SQL Injection)
$sql = "INSERT INTO products (name, description, image, price, stock, category) VALUES (?, ?, NULL, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    // Cast ke tipe yang sesuai
    $harga_float = (float) $harga;
    $stock_int = (int) $stock;

    // Parameter: s = string, d = double, i = integer
    $stmt->bind_param("ssdis", $nama_produk, $deskripsi, $harga_float, $stock_int, $kategori);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Berhasil! Produk <strong>" . htmlspecialchars($nama_produk) . "</strong> telah ditambahkan.";
    } else {
        $_SESSION['errors'] = ["Gagal menyimpan ke database: " . $stmt->error];
        $_SESSION['old_input'] = $_POST;
    }

    $stmt->close();
} else {
    $_SESSION['errors'] = ["Terjadi kesalahan pada struktur query SQL."];
    $_SESSION['old_input'] = $_POST;
}

$conn->close();

// Alihkan kembali ke halaman Tambah_Produk.php
header("Location: Tambah_Produk.php");
exit();
