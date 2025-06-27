'use strict';

// Logika ini akan berjalan setelah seluruh halaman dimuat
document.addEventListener('DOMContentLoaded', function () {
  // Mengambil elemen tabel dari HTML
  const ajaxTable = $('.datatables-ajax');

  // Jika tabel tidak ditemukan, hentikan eksekusi
  if (!ajaxTable.length) {
    return;
  }

  // Definisikan kolom-kolom untuk DataTables
  let columns = [
    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false }, // Kolom Nomor Urut
    { data: 'id', name: 'id' }, // Kolom ID Order
  ];

  // =======================================================
  // Logika untuk menampilkan kolom secara dinamis
  // =======================================================
  // Jika pengguna yang login BUKAN mitra, tambahkan kolom "Nama Mitra"
  if (window.myApp.role !== 'mitra') {
    columns.push({ data: 'mitra_name', name: 'mitra.name' }); // 'mitra.name' untuk searching/sorting di server
  }

  // Tambahkan kolom-kolom sisanya yang selalu muncul
  columns.push(
    { data: 'tanggal_pengajuan', name: 'created_at' }, // 'created_at' untuk sorting di server
    { data: 'status', name: 'status' },
    { data: 'aksi', name: 'aksi', orderable: false, searchable: false } // Tombol Aksi
  );

  // =======================================================
  // Inisialisasi DataTables
  // =================================komentar======================
  ajaxTable.DataTable({
    processing: true, // Tampilkan indikator loading
    serverSide: true, // Proses data di sisi server (lebih efisien untuk data besar)
    ajax: window.myApp.getDataUrl, // URL untuk mengambil data dari controller
    columns: columns, // Gunakan definisi kolom yang sudah kita buat
    // Opsi tambahan untuk bahasa (jika diperlukan)
    language: {
      "sProcessing": "Sedang memproses...",
      "sLengthMenu": "Tampilkan _MENU_ entri",
      "sZeroRecords": "Tidak ditemukan data yang sesuai",
      "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
      "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
      "sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
      "sInfoPostFix": "",
      "sSearch": "Cari:",
      "sUrl": "",
      "oPaginate": {
        "sFirst": "Pertama",
        "sPrevious": "Sebelumnya",
        "sNext": "Selanjutnya",
        "sLast": "Terakhir"
      }
    }
  });
});
