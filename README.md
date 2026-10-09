# SIMPUS-Mini — Jobsheet 8 (Koneksi PostgreSQL)

## Persiapan

1. Pastikan PostgreSQL berjalan dan ekstensi PHP `pdo_pgsql` aktif:
   `php -m | findstr pgsql` (Windows) / `php -m | grep pgsql`
2. Buat database dan jalankan skema (dari folder project):
   ```
   createdb -U postgres simpus_mini
   psql -U postgres -d simpus_mini -f sql/01_buku_anggota.sql
   psql -U postgres -d simpus_mini -f sql/02_latihan_tanggal_ditambahkan.sql
   ```
3. Sesuaikan `$user` / `$pass` di `includes/koneksi.php` bila perlu.
4. (Opsional, Latihan 4) pindahkan data lama: `php sql/migrasi_buku_json.php`

## Menjalankan

```
php -S localhost:8000
```
lalu buka http://localhost:8000/index.php (atau lewat Laragon).

## Catatan
- Data buku & anggota tersimpan permanen di PostgreSQL. `$_SESSION` hanya
  dipakai untuk pesan flash.
- Query memakai prepared statement (placeholder `:nama`).
