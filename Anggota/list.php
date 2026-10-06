<?php
$page_title = "Daftar Anggota";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$daftarAnggota = $_SESSION['anggota'] ?? [];
?>


      <section>
        <h2>List Anggota</h2>
        <?php if ($flash): ?>
            <div class="flash flash-<?php echo $flash["type"]; ?>">
                <?php echo htmlspecialchars($flash["pesan"]); ?>
            </div>
            <?php endif; ?>
        <div class="table-responsive">
          <div class="search-box">
            <label for="search-input">Cari nama anggota</label>
            <input
              type="text"
              id="search-input"
              placeholder="cari nama anggota..."
            />
          </div>
          <p id="search-count"></p>
          <p id="loading">Memuat data...</p>
          <table>
            <thead>
              <tr>
                <th>No. Anggota</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>No. HP</th>
                <th>Jenis Kelamin</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
               <?php if (empty($daftarAnggota)): ?>
                    <tr>
                        <td colspan="6">Belum ada data. Tambahkan lewat menu "Tambah Anggota".</td>
                    </tr>
                    <?php endif; ?>
                    <?php foreach ($daftarAnggota as $anggota): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($anggota["no_anggota"]); ?></td>
                        <td><?php echo htmlspecialchars($anggota["nama"]); ?></td>
                        <td><?php echo htmlspecialchars($anggota["alamat"]); ?></td>
                        <td><?php echo htmlspecialchars($anggota["no_hp"]); ?></td>
                        <td><?php echo htmlspecialchars($anggota["email"]); ?></td>
                        <td>
                            <button type="button">Edit</button>
                            <button type="button" class="btn-hapus">Hapus</button>
                        </td>
                        </tr>
                    <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
