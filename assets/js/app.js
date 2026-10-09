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
  document.addEventListener("click", function (e) {
    const btn = e.target.closest(".btn-hapus");
    if (!btn) return;

    const row = btn.closest("tr");
    const nama = row ? row.querySelector("td")?.textContent : "data ini";
    const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");
    if (yakin && row) row.remove();
  });
}

// ===== Filter/pencarian tabel real-time =====
function initTableFilter() {
  const input = document.getElementById("search-input");
  const table = document.querySelector(".table-responsive table");
  const countEl = document.getElementById("search-count");
  if (!input || !table) return;

  const colIndex = parseInt(input.dataset.filterCol || "0", 10);

  function tampilkanJumlah(visible, total) {
    if (countEl && total > 0) {
      countEl.textContent = "Menampilkan " + visible + " dari " + total + " data";
    }
  }

  function saring() {
    const keyword = input.value.toLowerCase();
    const rows = table.querySelectorAll("tbody tr");
    let visibleCount = 0;
    let totalCount = 0;

    rows.forEach(function (row) {
      if (row.cells.length <= 1) return; 
      totalCount++;

      const cell = row.cells[colIndex];
      const teks = cell ? cell.textContent.toLowerCase() : "";

      if (teks.includes(keyword)) {
        row.style.display = "";
        visibleCount++;
      } else {
        row.style.display = "none";
      }
    });

    tampilkanJumlah(visibleCount, totalCount);
  }

  input.addEventListener("keyup", saring);
  saring(); 
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

document.addEventListener("DOMContentLoaded", function () {
  initNavToggle();
  initHapusConfirm();
  initTableFilter();
  initValidasiForm();
});