<?php
$page_title = "Daftar Buku";
require __DIR__ . '/../includes/koneksi.php';
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $stmt = $pdo->prepare("SELECT * FROM buku WHERE judul ILIKE :keyword ORDER BY id DESC");
    $stmt->execute(['keyword' => '%' . addcslashes($q, '%_\\') . '%']);
    $daftarBuku = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $daftarBuku = $pdo->query("SELECT * FROM buku ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}
?>
<section>
  <h2>List Buku</h2>

  <?php if ($flash): ?>
    <p class="flash flash-<?php echo htmlspecialchars($flash['type']); ?>"><?php echo htmlspecialchars($flash['pesan']); ?></p>
  <?php endif; ?>

  <div class="table-responsive">
    <!-- Ketik = saring cepat di browser (app.js). Enter / tombol Cari = cari di database (server). -->
    <form class="search-box" method="get" action="list.php">
      <label for="search-input">Cari Judul Buku</label>
      <input type="text" id="search-input" name="q" data-filter-col="0"
        value="<?php echo htmlspecialchars($q); ?>" placeholder="Cari judul buku..." />
      <button type="submit">Cari</button>
      <?php if ($q !== ''): ?><a href="list.php">Reset</a><?php endif; ?>
    </form>
    <p id="search-count"></p>

    <table>
      <thead>
        <tr>
          <th>Judul</th>
          <th>Pengarang</th>
          <th>Tahun</th>
          <th>Stok</th>
          <th>Ditambahkan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($daftarBuku)): ?>
          <tr>
            <td colspan="6">
              <?php if ($q !== ''): ?>
                Tidak ada buku dengan judul "<?php echo htmlspecialchars($q); ?>".
              <?php else: ?>
                Belum ada data buku. Silakan tambah lewat menu "Tambah Buku".
              <?php endif; ?>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($daftarBuku as $buku): ?>
            <tr>
              <td><?php echo htmlspecialchars($buku['judul']); ?></td>
              <td><?php echo htmlspecialchars($buku['pengarang']); ?></td>
              <td><?php echo (int) $buku['tahun']; ?></td>
              <td><?php echo (int) $buku['stok']; ?></td>
              <td>
                <?php
                echo !empty($buku['tanggal_ditambahkan'])
                    ? htmlspecialchars(date('d-m-Y H:i', strtotime($buku['tanggal_ditambahkan'])))
                    : '-';
                ?>
              </td>
              <td>
                <button type="button">Edit</button>
                <button type="button" class="btn-hapus">Hapus</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>
<?php include __DIR__ . '/../includes/footer.php'; ?>