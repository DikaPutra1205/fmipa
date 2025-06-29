'use strict';

document.addEventListener('DOMContentLoaded', function () {
  let dtServiceTable = $('.datatables-ajax');
  let serviceTable;

  // Definisi kolom
  let columns = [
    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
    { data: 'module', name: 'module.name' },
    { data: 'name', name: 'name' },
    { data: 'price', name: 'price' },
  ];

  if (window.userRole === 'admin') {
    columns.push({
      data: 'aksi',
      orderable: false,
      searchable: false
    });
  }

  // Inisialisasi DataTables
  if (dtServiceTable.length) {
    window.testPackageTable = dtServiceTable.DataTable({
      processing: true,
      serverSide: true,
      ajax: window.routes.getTestPackageData,
      columns: columns,
      order: [[0, 'asc']],
      dom:
        '<"row mx-2"' +
        '<"col-md-2"l>' +
        '<"col-md-10 text-end"fB>>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>>',
      responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: (row) => 'Detail Paket ' + row.data().name
          }),
          renderer: (api, rowIdx, columns) => {
            return $('<table class="table"/>')
              .append(
                $('<tbody/>').append(
                  columns
                    .filter(col => !(col.columnIndex === columns.length - 1 && window.userRole === 'admin'))
                    .map(col =>
                      `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
                          <td>${col.title}</td><td>${col.data}</td>
                       </tr>`
                    )
                    .join('')
                )
              );
          }
        }
      }
    });
  }

  // Modal Hapus
  const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
  const btnConfirmDelete = document.getElementById('btnConfirmDelete');
  const modalDeleteNameSpan = document.getElementById('modalDeleteName');
  const modalDeleteIdSpan = document.getElementById('modalDeleteId');

  let currentTestPackageIdToDelete = null;

  if (!deleteConfirmationModal || !btnConfirmDelete || !modalDeleteNameSpan || !modalDeleteIdSpan) {
    console.error('ERROR: Salah satu elemen modal tidak ditemukan. Periksa ID elemen modal.');
    return;
  }

  // Saat modal akan muncul
  deleteConfirmationModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;

    const id = button.getAttribute('data-id');
    const name = button.getAttribute('data-name'); // atau data-test-package-name, sesuaikan

    modalDeleteNameSpan.textContent = name;
    modalDeleteIdSpan.textContent = id;

    currentTestPackageIdToDelete = id;
  });

  // Saat klik tombol konfirmasi hapus
  btnConfirmDelete.addEventListener('click', function () {
    if (!currentTestPackageIdToDelete) return;

    fetch(`${window.routes.deleteTestPackage}/${currentTestPackageIdToDelete}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': window.routes.csrfToken,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    })
      .then(res => {
        if (!res.ok) {
          return res.json().then(err => {
            throw new Error(err.message || 'Gagal menghapus data.');
          });
        }
        return res.json();
      })
      .then(data => {
        const modalInstance = bootstrap.Modal.getInstance(deleteConfirmationModal);
        modalInstance.hide();

        alert(data.message || 'Data berhasil dihapus.');

        if (window.testPackageTable) {
          window.testPackageTable.ajax.reload(null, false);
        }
      })
      .catch(err => {
        const modalInstance = bootstrap.Modal.getInstance(deleteConfirmationModal);
        modalInstance.hide();

        alert('Error: ' + err.message);
        console.error(err);
      });
  });
});
