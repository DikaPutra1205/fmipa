document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi array kolom
    let columns = [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'nama_sampel_material', name: 'nama_sampel_material' },
        { data: 'jumlah_sampel', name: 'jumlah_sampel' },
        { data: 'tanggal_penerimaan', name: 'tanggal_penerimaan' },
        { data: 'tanggal_pengembalian', name: 'tanggal_pengembalian' },
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
        ajax: '/sample-material/data',
        columns: columns
    });

});

