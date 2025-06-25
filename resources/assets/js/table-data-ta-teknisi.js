document.addEventListener('DOMContentLoaded', function () {
    // A. LOGIKA UNTUK MENAMPILKAN DATA DI DATATABLES
    let columns = [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'name', name: 'name' }, // Kolom 'name' dari model User
        { data: 'email', name: 'email' }, // Kolom 'email' dari model User
        { data: 'role', name: 'role' },
        { data: 'coordinator_name', name: 'coordinator_name' },
        { data: 'phone', name: 'phone' }, // Kolom 'phone' dari model User
        { data: 'is_active', name: 'is_active' } // Kolom 'is_active' dari model User
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
        ajax: window.routes.getTeknisiData, // Gunakan URL dari window.routes
        columns: columns
    });

    // B. LOGIKA UNTUK MODAL KONFIRMASI HAPUS
    const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
    const btnConfirmDelete = document.getElementById('btnConfirmDelete');
    const modalUserNameSpan = document.getElementById('modalUserName');
    const modalUserIdSpan = document.getElementById('modalUserId');

    // --- DEBUGGING AWAL ---
    console.log('Element deleteConfirmationModal:', deleteConfirmationModal);
    console.log('Element btnConfirmDelete:', btnConfirmDelete);
    console.log('Element modalUserNameSpan:', modalUserNameSpan);
    console.log('Element modalUserIdSpan:', modalUserIdSpan);
    // --- AKHIR DEBUGGING AWAL ---

    if (!deleteConfirmationModal || !btnConfirmDelete || !modalUserNameSpan || !modalUserIdSpan) {
        console.error('Salah satu elemen modal konfirmasi hapus tidak ditemukan. Pastikan ID HTML sudah benar.');
        return; // Hentikan eksekusi jika elemen penting tidak ditemukan
    }

    let currentUserIdToDelete = null; // Variabel untuk menyimpan ID user yang akan dihapus

    // Event listener saat modal akan ditampilkan
    // Ini dipicu oleh Bootstrap secara otomatis ketika tombol dengan data-bs-toggle diklik
    deleteConfirmationModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Tombol yang memicu modal
        currentUserIdToDelete = button.getAttribute('data-id'); // Ambil ID user dari tombol
        const userName = button.getAttribute('data-user-name'); // Ambil nama user dari tombol

        // Isi konten modal dengan informasi user
        modalUserNameSpan.textContent = userName;
        modalUserIdSpan.textContent = currentUserIdToDelete;

        // Atur ID user yang akan dihapus pada tombol konfirmasi di modal
        btnConfirmDelete.setAttribute('data-id', currentUserIdToDelete);

        console.log('Modal dibuka untuk user ID:', currentUserIdToDelete, 'Nama:', userName); // DEBUGGING
    });

    // Event listener saat tombol "Hapus Data" di dalam modal diklik
    btnConfirmDelete.addEventListener('click', function() {
        console.log('Tombol Hapus Data di dalam modal diklik!'); // DEBUGGING: Konfirmasi klik
        const id = this.getAttribute('data-id'); // Ambil ID dari tombol konfirmasi modal
        console.log('ID user yang akan dihapus:', id); // DEBUGGING: Tampilkan ID
        console.log('CSRF Token yang akan dikirim:', window.routes.csrfToken); // DEBUGGING: Pastikan token ada

        fetch(window.routes.deleteTeknisi + '/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': window.routes.csrfToken, // <--- UBAH DI SINI! Gunakan variabel global
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
            alert(data.message || 'Data berhasil dihapus.');
            if (window.userTable) {
                window.userTable.ajax.reload(null, false); // Reload DataTables
            }
        })
        .catch(error => {
            const modalInstance = bootstrap.Modal.getInstance(deleteConfirmationModal);
            if (modalInstance) {
                modalInstance.hide(); // Sembunyikan modal jika ada error juga
            }
            alert('Error: ' + error.message);
            console.error('Error deleting user:', error);
        });
    });
});
