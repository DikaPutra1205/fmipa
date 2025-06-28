document.addEventListener('DOMContentLoaded', function () {
    // A. LOGIKA UNTUK MENAMPILKAN DATA DI DATATABLES
    let columns = [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'code', name: 'code' }, // Nama kolom dari model module
        { data: 'name', name: 'name' },     // Nama kolom dari model module
        { data: 'description', name: 'description' },       // Nama kolom dari model module
        { data: 'details', name: 'details' }       // Nama kolom dari model module
    ];

    // Tambahkan kolom aksi jika user login sebagai admin
    if (window.userRole === 'admin') {
        columns.push({
            data: 'aksi',
            orderable: false,
            searchable: false
        });
    }

    // Inisialisasi DataTables
    window.moduleTable = $('.datatables-ajax').DataTable({
        processing: true,
        serverSide: true,
        ajax: window.routes.getModuleData,
        columns: columns
    });

    // B. LOGIKA UNTUK MODAL KONFIRMASI HAPUS
    const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
    const btnConfirmDelete = document.getElementById('btnConfirmDelete');
    const modalModuleNameSpan = document.getElementById('modalModuleName');
    const modalModuleIdSpan = document.getElementById('modalModuleId');

    // --- DEBUGGING AWAL: Memastikan semua elemen ditemukan ---
    console.log('Modal element:', deleteConfirmationModal);
    console.log('Confirm delete button:', btnConfirmDelete);
    console.log('Module Name Span in modal:', modalModuleNameSpan);
    console.log('Module ID Span in modal:', modalModuleIdSpan);
    // --- AKHIR DEBUGGING AWAL ---

    if (!deleteConfirmationModal || !btnConfirmDelete || !modalModuleNameSpan || !modalModuleIdSpan) {
        console.error('ERROR: Salah satu elemen modal konfirmasi hapus untuk Module tidak ditemukan. Pastikan ID HTML sudah benar.');
        return; // Hentikan eksekusi jika elemen penting tidak ditemukan
    }

    let currentmoduleIdToDelete = null; // Variabel untuk menyimpan ID alat/bahan yang akan dihapus

    // Event listener saat modal akan ditampilkan (dipicu oleh Bootstrap secara otomatis)
    deleteConfirmationModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Tombol yang memicu modal (yaitu tombol "Hapus" di DataTables)
        currentmoduleIdToDelete = button.getAttribute('data-id'); // Ambil ID alat/bahan dari tombol
        const moduleName = button.getAttribute('data-module-name'); // Ambil nama alat/bahan dari tombol

        // Isi konten modal dengan informasi alat/bahan
        modalModuleNameSpan.textContent = moduleName;
        modalModuleIdSpan.textContent = currentmoduleIdToDelete;

        // Atur ID alat/bahan yang akan dihapus pada tombol konfirmasi di modal
        btnConfirmDelete.setAttribute('data-id', currentmoduleIdToDelete);

        console.log('Modal dibuka untuk Module ID:', currentmoduleIdToDelete, 'Nama:', moduleName); // DEBUGGING
    });

    // Event listener saat tombol "Hapus Data" di dalam modal diklik
    btnConfirmDelete.addEventListener('click', function () {
        console.log('Tombol Hapus Data di dalam modal diklik!'); // DEBUGGING: Konfirmasi klik
        const id = this.getAttribute('data-id'); // Ambil ID dari tombol konfirmasi modal
        console.log('ID alat/bahan yang akan dihapus:', id); // DEBUGGING: Tampilkan ID
        console.log('CSRF Token yang akan dikirim:', window.routes.csrfToken); // DEBUGGING: Pastikan token ada

        fetch(window.routes.deletemodule + '/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': window.routes.csrfToken, // Menggunakan CSRF token dari window.routes
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
            .then(res => {
                if (!res.ok) {
                    // Jika respons bukan 2xx (misal 403, 500), baca pesan error dari JSON respons
                    return res.json().then(errorData => {
                        throw new Error(errorData.message || 'Gagal menghapus data Module.');
                    });
                }
                return res.json(); // Jika respons OK, parse JSON
            })
            .then(data => {
                // Sembunyikan modal setelah berhasil
                const modalInstance = bootstrap.Modal.getInstance(deleteConfirmationModal);
                if (modalInstance) {
                    modalInstance.hide();
                }
                alert(data.message || 'Data Module berhasil dihapus.');
                // Reload DataTables
                if (window.moduleTable) {
                    window.moduleTable.ajax.reload(null, false); // `false` agar tidak kembali ke halaman 1
                }
            })
            .catch(error => {
                // Sembunyikan modal jika ada error juga
                const modalInstance = bootstrap.Modal.getInstance(deleteConfirmationModal);
                if (modalInstance) {
                    modalInstance.hide();
                }
                alert('Error: ' + error.message);
                console.error('Error deleting Module:', error);
            });
    });
});
