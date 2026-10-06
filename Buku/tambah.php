<?php
$page_title = "Tambah Buku";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<section>

  <h2>Tambah Buku</h2>
  <?php if ($flash): ?>
            <div class="flash flash-<?php echo $flash["type"]; ?>">
                <?php echo htmlspecialchars($flash["pesan"]); ?>
            </div>
            <?php endif; ?>

  <form id="form-tambah">
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
      <input type="text" id="isbn" name="isbn" min="0" required /><br /><br />
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
