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

// 2. Pastikan request datang dari method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Ambil data dari form & bersihkan input
    $nama_produk = trim($_POST['nama_produk'] ?? '');
    $kategori    = trim($_POST['kategori'] ?? '');
    $harga       = trim($_POST['harga'] ?? '');
    $stock       = trim($_POST['stock'] ?? '');
    $deskripsi   = trim($_POST['deskripsi'] ?? '');

    $errors = [];

    // 3. Validasi Input
    if (empty($nama_produk)) {
        $errors[] = "Nama produk wajib diisi.";
    }
    if (empty($kategori)) {
        $errors[] = "Kategori produk wajib diisi.";
    }
    if ($harga === '' || !is_numeric($harga) || $harga < 0) {
        $errors[] = "Harga harus berupa angka valid dan tidak boleh minus.";
    }
    if ($stock === '' || !is_numeric($stock) || $stock < 0) {
        $errors[] = "Stok harus berupa angka valid dan tidak boleh minus.";
    }
    if (empty($deskripsi)) {
        $errors[] = "Deskripsi produk wajib diisi.";
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
        // Parameter: s = string, i = integer
        $stmt->bind_param("ssiis", $nama_produk, $deskripsi, $harga, $stock, $kategori);

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

} else {
    // Jika file diakses langsung tanpa lewat form POST
    header("Location: Tambah_Produk.php");
    exit();
}