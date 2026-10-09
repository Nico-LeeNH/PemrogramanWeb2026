<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$nama       = trim($_POST['nama'] ?? '');
$email      = trim($_POST['email'] ?? '');
$no_anggota = trim($_POST['no_anggota'] ?? '');
$alamat     = trim($_POST['alamat'] ?? '');
$no_hp      = trim($_POST['no_hp'] ?? '');

$errors = [];

if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}
if ($no_anggota === '') {
    $errors[] = "No. Anggota wajib diisi.";
} elseif (strlen($no_anggota) > 50) {
    $errors[] = "No. Anggota maksimal 50 karakter.";
}
if ($email === '') {
    $errors[] = "Email wajib diisi.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Format email tidak valid.";
}
if ($no_hp !== '' && !preg_match('/^[0-9]{8,15}$/', $no_hp)) {
    $errors[] = "No. HP harus berupa angka 8-15 digit.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: tambah.php');
    exit;
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO anggota (nama, no_anggota, alamat, no_hp, email)
         VALUES (:nama, :no_anggota, :alamat, :no_hp, :email)
         RETURNING id"
    );
    $stmt->execute([
        'nama'       => $nama,
        'no_anggota' => $no_anggota,
        'alamat'     => $alamat !== '' ? $alamat : null,
        'no_hp'      => $no_hp !== '' ? $no_hp : null,
        'email'      => $email,
    ]);
    $idBaru = $stmt->fetchColumn();
} catch (PDOException $e) {
    if ($e->getCode() === '23505') {
        $pesan = "No. Anggota sudah dipakai, gunakan nomor lain.";
    } else {
        $pesan = "Gagal menyimpan anggota ke database. Coba lagi.";
    }
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => $pesan];
    header('Location: tambah.php');
    exit;
}

$_SESSION['flash'] = ['type' => 'success', 'pesan' => "Anggota \"$nama\" berhasil ditambahkan."];
header('Location: list.php');
exit;