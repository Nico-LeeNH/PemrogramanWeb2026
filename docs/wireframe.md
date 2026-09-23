# Wireframe SIMPUS-Mini

## Alur Halaman

```
Beranda (index.html)
 ├── Daftar Buku (buku/list.html) ──► Tambah Buku (buku/tambah.html)
 └── Daftar Anggota (anggota/list.html) ──► Tambah Anggota (anggota/tambah.html)
```

## Tata Letak Umum Tiap Halaman

```
┌─────────────────────────────────────────────┐
│ Header: [Brand]           [Nav ...] [☰]      │
├─────────────────────────────────────────────┤
│ Main:                                        │
│   - Judul halaman                            │
│   - (List) Search box + Loading + Tabel      │
│   - (Tambah) Form input + tombol Simpan      │
├─────────────────────────────────────────────┤
│ Footer                                       │
└─────────────────────────────────────────────┘
```

Pada layar sempit (≤480px), menu navigasi disembunyikan di balik tombol
hamburger (☰) dan dibuka/ditutup lewat `classList.toggle("nav-open")`.
