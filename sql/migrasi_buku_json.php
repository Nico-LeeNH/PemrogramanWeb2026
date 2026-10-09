<?php

require __DIR__ . '/../includes/koneksi.php';

$file = __DIR__ . '/../data/buku.json';
if (!file_exists($file)) {
    die("File data/buku.json tidak ditemukan.\n");
}

$daftar = json_decode(file_get_contents($file), true);
if (!is_array($daftar)) {
    die("Isi data/buku.json bukan JSON yang valid: " . json_last_error_msg() . "\n");
}

$cek = $pdo->prepare("SELECT 1 FROM buku WHERE judul = :judul AND pengarang = :pengarang");
$insert = $pdo->prepare(
    "INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori)
     VALUES (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)"
);

$masuk = 0;
$lewat = 0;

$pdo->beginTransaction();
try {
    foreach ($daftar as $b) {
        $cek->execute(['judul' => $b['judul'], 'pengarang' => $b['pengarang']]);
        if ($cek->fetchColumn()) {
            $lewat++;
            continue;
        }
        $insert->execute([
            'judul'     => $b['judul'],
            'pengarang' => $b['pengarang'],
            'tahun'     => (int) $b['tahun'],
            'isbn'      => $b['isbn'] ?? null,
            'stok'      => (int) ($b['stok'] ?? 0),
            'kategori'  => $b['kategori'] ?? null,
        ]);
        $masuk++;
    }
    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();
    die("Migrasi dibatalkan: " . $e->getMessage() . "\n");
}

echo "Selesai. Dimasukkan: $masuk buku, dilewati (sudah ada): $lewat buku.\n";
