<?php
$page_title = "Tambah Buku";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<section>
  <h2>Tambah Buku</h2>

  <?php if ($flash): ?>
    <p class="flash flash-<?php echo htmlspecialchars($flash['type']); ?>"><?php echo htmlspecialchars($flash['pesan']); ?></p>
  <?php endif; ?>

  <!-- method="post" + action WAJIB ada, kalau tidak data tidak pernah terkirim ke server -->
  <form id="form-tambah" method="post" action="proses_tambah.php">
    <p>
      <label for="judul">Judul</label><br />
      <input type="text" id="judul" name="judul" /><br /><br />
    </p>
    <p>
      <label for="pengarang">Pengarang</label><br />
      <input type="text" id="pengarang" name="pengarang" /><br /><br />
    </p>
    <p>
      <label for="tahun_terbit">Tahun Terbit</label><br />
      <input type="text" id="tahun_terbit" name="tahun" /><br /><br />
    </p>
    <p>
      <label for="isbn">ISBN</label><br />
      <input type="text" id="isbn" name="isbn" required /><br /><br />
    </p>
    <p>
      <label for="stok">Stok</label><br />
      <input type="text" id="stok" name="stok" /><br /><br />
    </p>
    <p>
      <label for="kategori">Kategori</label><br />
      <select id="kategori" name="kategori">
        <option value="fiksi">Fiksi</option>
        <option value="non_fiksi">Non-Fiksi</option>
        <option value="referensi">Referensi</option>
      </select>
    </p>
    <p>
      <button type="submit">Simpan</button>
    </p>
  </form>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>