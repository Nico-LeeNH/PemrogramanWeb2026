// ===== Menu hamburger (JS-driven) =====
function initNavToggle() {
  const toggleBtn = document.getElementById("nav-toggle-btn");
  const nav = document.querySelector("header nav");
  if (!toggleBtn || !nav) return;

  toggleBtn.addEventListener("click", function () {
    nav.classList.toggle("nav-open");
  });
}

// ===== Konfirmasi hapus (front-end only, belum ke server) =====
function initHapusConfirm() {
  document.querySelectorAll(".btn-hapus").forEach(function (btn) {
    btn.addEventListener("click", function () {
      const row = btn.closest("tr");
      const nama = row ? row.querySelector("td")?.textContent : "data ini";
      const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");
      if (yakin && row) row.remove();
    });
  });
}

// ===== Filter/pencarian tabel real-time =====
function initTableFilter(targetColIndex = 0) {
  const input = document.getElementById("search-input");
  const table = document.querySelector(".table");
  const countEl = document.getElementById("search-count");
  if (!input || !table) return;

  input.addEventListener("keyup", function () {
    const keyword = input.value.toLowerCase();
     const rows = table.querySelectorAll("tbody tr");
        
        let visibleCount = 0; // 2. Variabel buat ngitung baris yang tampil
        let totalCount = 0;   // 2. Variabel buat ngitung total baris data
     rows.forEach(function (row) {
            if (row.cells.length <= 1) return; // Lewati baris error/loading
            
            totalCount++; // Tambah total baris data

            const judulText = row.cells[targetColIndex] ? row.cells[targetColIndex].textContent.toLowerCase() : "";
            
            if (judulText.includes(keyword)) {
                row.style.display = "";
                visibleCount++; // Tambah hitungan jika baris cocok
            } else {
                row.style.display = "none";
            }
        });

        // tugas mandiri no 3
        if (countEl && totalCount > 0) {
            countEl.textContent = `Menampilkan ${visibleCount} dari ${totalCount} buku`;
        }
    });
}

// ===== Validasi form (client-side) =====
function tampilkanError(input, pesan) {
  hapusError(input);
  const span = document.createElement("span");
  span.className = "error";
  span.textContent = pesan;
  input.insertAdjacentElement("afterend", span);
}

function hapusError(input) {
  const next = input.nextElementSibling;
  if (next && next.classList.contains("error")) {
    next.remove();
  }
}

function initValidasiForm() {
  const form = document.getElementById("form-tambah");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    let valid = true;

    // Field teks wajib: judul (buku) / nama (anggota)
    const judulNama = form.querySelector("[name='judul'], [name='nama']");
    if (judulNama && judulNama.value.trim() === "") {
      tampilkanError(judulNama, "Field ini wajib diisi.");
      valid = false;
    } else if (judulNama) {
      hapusError(judulNama);
    }

    const isbn = form.querySelector("[name='isbn']");
        if (isbn) {
            const nilaiIsbn = isbn.value.trim();
            const isbnRegex = /^[0-9-]+$/;

            if (nilaiIsbn === "") {
                tampilkanError(isbn, "ISBN wajib diisi.");
                valid = false;
            } else if (!isbnRegex.test(nilaiIsbn)) {
                tampilkanError(isbn, "ISBN hanya boleh berisi angka dan tanda hubung (-).");
                valid = false;
            } else {
                hapusError(isbn);
            }
        }
    const pengarang = form.querySelector("[name='pengarang']");
    if (pengarang) {
      if (pengarang.value.trim() === "") {
        tampilkanError(pengarang, "Field ini wajib diisi.");
        valid = false;
      } else {
        hapusError(pengarang);
      }
    }

    // No. Anggota (khusus form Anggota)
    const noAnggota = form.querySelector("[name='no_anggota']");
    if (noAnggota) {
      if (noAnggota.value.trim() === "") {
        tampilkanError(noAnggota, "Field ini wajib diisi.");
        valid = false;
      } else {
        hapusError(noAnggota);
      }
    }

    // Tahun terbit (khusus form Buku): angka, 1900-2026
    const tahun = form.querySelector("[name='tahun']");
    if (tahun) {
      const nilaiTahun = parseInt(tahun.value, 10);
      if (tahun.value.trim() === "" || isNaN(nilaiTahun) || nilaiTahun < 1900 || nilaiTahun > 2026) {
        tampilkanError(tahun, "Masukkan tahun yang valid (1900-2026).");
        valid = false;
      } else {
        hapusError(tahun);
      }
    }

    // Stok (khusus form Buku): angka, minimal 0
    const stok = form.querySelector("[name='stok']");
    if (stok) {
      const nilaiStok = parseInt(stok.value, 10);
      if (stok.value.trim() === "" || isNaN(nilaiStok) || nilaiStok < 0) {
        tampilkanError(stok, "Masukkan angka stok yang valid (minimal 0).");
        valid = false;
      } else {
        hapusError(stok);
      }
    }

    // ...(pengecekan pengarang, tahun, stok dengan pola serupa)...
    if (!valid) {
      e.preventDefault();

    }

  });
}

// ===== Fungsi Generik Gabungan =====
async function muatDataGenerik(config) {
    const tbody = document.getElementById(config.tbodyId);
    const loading = document.getElementById("loading");
    if (!tbody) return;

    if (loading) loading.style.display = "block";
    tbody.innerHTML = "";

    try {
        // ===== Delay diset 3000 ms =====
        await new Promise(resolve => setTimeout(resolve, config.delay || 3000));

        const res = await fetch(config.url);
        if (!res.ok) {
            throw new Error("Gagal mengambil data (status " + res.status + ")");
        }

        const dataList = await res.json();

        dataList.forEach(item => {
            const tr = document.createElement("tr");
            tr.innerHTML = config.renderRow(item);
            tbody.appendChild(tr);
        });

        // Set penghitung awal data
        const countEl = document.getElementById("search-count");
        if (countEl) {
            countEl.textContent = `Menampilkan ${dataList.length} dari ${dataList.length} data`;
        }
    } catch (err) {
        tbody.innerHTML = `<tr><td colspan='${config.colSpan || 5}'>Gagal memuat data: ${err.message}</td></tr>`;
    } finally {
        if (loading) loading.style.display = "none";
    }
}

// ===== Jalankan semua fungsi setelah DOM siap =====
document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initValidasiForm();
});

// ===== Titik masuk (entry point) =====
document.addEventListener("DOMContentLoaded", function () {
  initNavToggle();
  initHapusConfirm();
  initTableFilter();
  initValidasiForm();
});
