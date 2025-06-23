document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi array kolom
    let columns = [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'nama_alat_bbahan', name: 'nama_alat_bbahan' },
        { data: 'kondisi_alat', name: 'kondisi_alat' },
        { data: 'jumlah_alat', name: 'jumlah_alat' },
        { data: 'status_data', name: 'status_data' }
    ];

    // Tambahkan kolom aksi jika login sebagai admin
    if (window.userRole === 'admin') {
        columns.push({
            data: 'aksi',
            orderable: false,
            searchable: false
        });
    }
    window.userTable = $('.datatables-ajax').DataTable({
        processing: true,
        serverSide: true,
        ajax: '/alat-bahan/data',
        columns: columns
    });

});

