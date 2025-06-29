'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const ajaxTable = $('.datatables-ajax');
    if (!ajaxTable.length) return;

    let columns = [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'id', name: 'id' },
    ];

    // Jika bukan mitra, tampilkan kolom nama mitra
    if (window.userRole !== 'mitra') {
        columns.push({ data: 'mitra_name', name: 'mitra.name' });
    }

    // Tambahkan sisa kolom
    columns.push(
        { data: 'module_name', name: 'module.name' },
        { data: 'package_name', name: 'testPackage.name' },
        { data: 'completion_date', name: 'updated_at' },
        { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
    );

    ajaxTable.DataTable({
        processing: true,
        serverSide: true,
        ajax: window.routes.getHasilUjiData,
        columns: columns,
        language: {
            // Opsi bahasa Indonesia bisa ditambahkan di sini
            "sProcessing": "Memuat...",
            "sSearch": "Cari:",
            "oPaginate": {
                "sPrevious": "Sebelumnya",
                "sNext": "Selanjutnya"
            }
        }
    });
});