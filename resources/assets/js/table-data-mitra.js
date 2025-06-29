document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi array kolom
    let columns = [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'institution', name: 'institution' },
        { data: 'name', name: 'name' },
        { data: 'email', name: 'email' },
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
        ajax: '/mitra/data',
        columns: columns
    });
    // Event klik tombol hapus
    $(document).on('click', '.btn-delete-user', function () {
        const userId = $(this).data('id');
        const row = $(this).closest('tr');
        const userName = row.find('td:nth-child(2)').text(); // Nama Institusi

        $('#modalUserId').text(userId);
        $('#modalUserName').text(userName);
        $('#btnConfirmDelete').data('id', userId);
        $('#deleteConfirmationModal').modal('show');
    });
    $('#btnConfirmDelete').on('click', function () {
        const userId = $(this).data('id');
    
        $.ajax({
            url: window.routes.deleteTeknisi + '/' + userId,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': window.routes.csrfToken
            },
            success: function (response) {
                $('#deleteConfirmationModal').modal('hide');
                window.userTable.ajax.reload(null, false);
                alert(response.message);
            },
            error: function (xhr) {
                alert('Gagal menghapus data: ' + (xhr.responseJSON?.message || 'Terjadi kesalahan'));
            }
        });
    });
    


});

