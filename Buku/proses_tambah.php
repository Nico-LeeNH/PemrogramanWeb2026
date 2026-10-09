<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$judul     = trim($_POST['judul'] ?? '');
$pengarang = trim($_POST['pengarang'] ?? '');
$tahun     = trim($_POST['tahun'] ?? '');
$isbn      = trim($_POST['isbn'] ?? '');
$stok      = trim($_POST['stok'] ?? '');
$kategori  = trim($_POST['kategori'] ?? '');

$errors = [];

if ($judul === '') {
    $errors[] = "Judul wajib diisi.";
}
if ($pengarang === '') {
    $errors[] = "Pengarang wajib diisi.";
}
if (filter_var($tahun, FILTER_VALIDATE_INT) === false || $tahun < 1900 || $tahun > 2026) {
    $errors[] = "Tahun harus berupa angka di antara 1900-2026.";
}
if (filter_var($stok, FILTER_VALIDATE_INT) === false || $stok < 0) {
    $errors[] = "Stok harus berupa angka dan tidak boleh negatif.";
}
// ISBN boleh kosong (kolom di database nullable), tapi jika diisi
// hanya boleh berisi angka dan tanda hubung.
if ($isbn !== '' && !preg_match('/^[0-9\-]+$/', $isbn)) {
    $errors[] = "ISBN hanya boleh berisi angka dan tanda hubung (-).";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;
}

// ===== Simpan ke PostgreSQL =====
try {
    $stmt = $pdo->prepare(
        "INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori)
         VALUES (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)
         RETURNING id"
    );
    $stmt->execute([
        'judul'     => $judul,
        'pengarang' => $pengarang,
        'tahun'     => (int) $tahun,
        'isbn'      => $isbn !== '' ? $isbn : null,
        'stok'      => (int) $stok,
        'kategori'  => $kategori !== '' ? $kategori : null,
    ]);
    $idBaru = $stmt->fetchColumn(); // id hasil RETURNING
} catch (PDOException $e) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal menyimpan buku ke database. Coba lagi.'];
    header('Location: tambah.php');
    exit;
}

$_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Buku berhasil ditambahkan.'];
header('Location: list.php');
exit;
