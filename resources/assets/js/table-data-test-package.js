/**
 * DataTables for Service Data (Test Package)
 */

'use strict';

$(function () {
  // A. LOGIKA UNTUK MENAMPILKAN DATA DI DATATABLES
  let dt_service_table = $('.datatables-ajax');
  let serviceTable; // Deklarasikan variabel untuk instance DataTables

  // Definisi kolom DataTables
  let columns = [
    { data: 'id' }, // Kolom ID, akan diubah menjadi nomor urut
    { data: 'module.name', name: 'module.name' }, // <--- UBAH DI SINI: Mengambil nama modul
    { data: 'name', name: 'name' },         // Kolom 'name' dari model Service
    { data: 'price', name: 'price' },       // Kolom 'price' dari model Service
    { data: 'created_at', name: 'created_at' }, // Kolom 'created_at' dari model Service
    { data: 'updated_at', name: 'updated_at' }  // Kolom 'updated_at' dari model Service
  ];

  // Tambahkan kolom aksi jika login sebagai admin
  if (window.userRole === 'admin') {
    columns.push({
      data: 'aksi',
      orderable: false,
      searchable: false
    });
  }

  // Inisialisasi DataTables
  if (dt_service_table.length) {
    serviceTable = dt_service_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: window.routes.getServiceData, // Gunakan URL dari window.routes (test_package.data)
      columns: columns,
      columnDefs: [
        {
          // Untuk nomor urut (No)
          targets: 0,
          render: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
          }
        },
        {
          // Formatting Harga
          targets: 3,
          render: function (data, type, row) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(data);
          }
        },
        {
          // Formatting Tanggal Dibuat
          targets: 4,
          render: function (data, type, row) {
            return moment(data).format('DD MMMMYYYY HH:mm');
          }
        },
        {
          // Formatting Tanggal Diperbarui
          targets: 5,
          render: function (data, type, row) {
            return moment(data).format('DD MMMMYYYY HH:mm');
          }
        },
        // Sembunyikan kolom "Aksi" jika user bukan admin
        {
          targets: -1, // Target kolom terakhir (Aksi)
          visible: window.userRole === 'admin' // Hanya tampilkan jika role adalah 'admin'
        }
      ],
      order: [[0, 'asc']], // Urutkan berdasarkan kolom No secara ascending
      dom:
        '<"row mx-2"' +
        '<"col-md-2"<"me-3"l>>' +
        '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              let data = row.data();
              return 'Detail Data Layanan ' + data['name'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            let data = $.map(columns, function (col, i) {
              // Exclude the "Aksi" column from details if it exists
              if (col.columnIndex === (columns.length - 1) && window.userRole === 'admin') {
                return ''; // Skip "Aksi" column
              }
              return '<tr data-dt-row="' +
                col.rowIndex +
                '" data-dt-column="' +
                col.columnIndex +
                '">' +
                '<td>' +
                col.title +
                ':' +
                '</td> ' +
                '<td>' +
                col.data +
                '</td>' +
                '</tr>';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }
    });
  }

  // B. LOGIKA UNTUK MODAL KONFIRMASI HAPUS
  const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
  const btnConfirmDelete = document.getElementById('btnConfirmDelete');
  const modalServiceNameSpan = document.getElementById('modalServiceName');
  const modalServiceIdSpan = document.getElementById('modalServiceId');

  // --- DEBUGGING AWAL ---
  console.log('Element deleteConfirmationModal:', deleteConfirmationModal);
  console.log('Element btnConfirmDelete:', btnConfirmDelete);
  console.log('Element modalServiceNameSpan:', modalServiceNameSpan);
  console.log('Element modalServiceIdSpan:', modalServiceIdSpan);
  // --- AKHIR DEBUGGING AWAL ---

  if (!deleteConfirmationModal || !btnConfirmDelete || !modalServiceNameSpan || !modalServiceIdSpan) {
    console.error('Salah satu elemen modal konfirmasi hapus tidak ditemukan. Pastikan ID HTML sudah benar.');
    // Tidak menghentikan eksekusi, hanya mencatat error
  }

  let currentServiceIdToDelete = null; // Variabel untuk menyimpan ID layanan yang akan dihapus

  // Event listener saat tombol hapus di DataTables diklik
  dt_service_table.on('click', '.btn-delete-service', function () {
    currentServiceIdToDelete = $(this).data('id');
    // Ambil nama layanan dari kolom ke-3 (index 2) dari baris yang diklik
    let serviceName = $(this).closest('tr').find('td:eq(2)').text();

    if (modalServiceIdSpan && modalServiceNameSpan) {
      modalServiceIdSpan.textContent = currentServiceIdToDelete;
      modalServiceNameSpan.textContent = serviceName;
    }

    // Tampilkan modal
    if (deleteConfirmationModal) {
      const modalInstance = new bootstrap.Modal(deleteConfirmationModal);
      modalInstance.show();
    }

    console.log('Modal dibuka untuk layanan ID:', currentServiceIdToDelete, 'Nama:', serviceName); // DEBUGGING
  });

  // Event listener saat tombol "Hapus Data" di dalam modal diklik
  if (btnConfirmDelete) {
    btnConfirmDelete.addEventListener('click', function () {
      console.log('Tombol Hapus Data di dalam modal diklik!'); // DEBUGGING: Konfirmasi klik
      const id = currentServiceIdToDelete; // Gunakan ID yang disimpan dari event sebelumnya
      console.log('ID layanan yang akan dihapus:', id); // DEBUGGING: Tampilkan ID
      console.log('CSRF Token yang akan dikirim:', window.routes.csrfToken); // DEBUGGING: Pastikan token ada

      if (!id) {
        toastr.error('ID layanan tidak ditemukan untuk dihapus.', 'Error!');
        return;
      }

      fetch(window.routes.deleteService + '/' + id, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': window.routes.csrfToken, // Gunakan variabel global
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        }
      })
        .then(res => {
          if (!res.ok) {
            return res.json().then(errorData => {
              throw new Error(errorData.message || 'Gagal menghapus data.');
            });
          }
          return res.json();
        })
        .then(data => {
          const modalInstance = bootstrap.Modal.getInstance(deleteConfirmationModal);
          if (modalInstance) {
            modalInstance.hide(); // Sembunyikan modal setelah berhasil
          }
          // Menggunakan Toastr untuk notifikasi
          toastr.success(data.message || 'Data berhasil dihapus.', 'Berhasil!', {
            closeButton: true,
            tapToDismiss: false,
            progressBar: true
          });
          if (serviceTable) {
            serviceTable.ajax.reload(null, false); // Reload DataTables
          }
        })
        .catch(error => {
          const modalInstance = bootstrap.Modal.getInstance(deleteConfirmationModal);
          if (modalInstance) {
            modalInstance.hide(); // Sembunyikan modal jika ada error juga
          }
          // Menggunakan Toastr untuk notifikasi error
          toastr.error(error.message || 'Terjadi kesalahan saat menghapus data layanan.', 'Error!', {
            closeButton: true,
            tapToDismiss: false,
            progressBar: true
          });
          console.error('Error deleting service:', error);
        });
    });
  }
});
