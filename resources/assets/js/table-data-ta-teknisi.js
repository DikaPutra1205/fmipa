document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi array kolom
    let columns = [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'name', name: 'name' },
        { data: 'role', name: 'role' },
        { data: 'coordinator_name', name: 'coordinator_name' },
        { data: 'phone', name: 'phone' },
        { data: 'is_active', name: 'is_active' }
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
        ajax: '/user/data',
        columns: columns
    });

});

