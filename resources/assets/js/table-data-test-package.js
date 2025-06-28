document.addEventListener('DOMContentLoaded', function () {
    // A. LOGIKA UNTUK MENAMPILKAN DATA DI DATATABLES
    let columns = [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'module_id', name: 'module_id' },
        { data: 'name', name: 'name' },
        { data: 'price', name: 'price' },
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
    window.alatBahanTable = $('.datatables-ajax').DataTable({
        processing: true,
        serverSide: true,
        ajax: window.routes.getTestPackageData, // Mengambil URL dari window.routes
        columns: columns
    });

    // B. LOGIKA UNTUK MODAL KONFIRMASI HAPUS
    const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
    const btnConfirmDelete = document.getElementById('btnConfirmDelete');
    const modalTestPackageNameSpan = document.getElementById('modalAlatBahanName');
    const modalTestPackageIdSpan = document.getElementById('modalAlatBahanId');

    // --- DEBUGGING AWAL: Memastikan semua elemen ditemukan ---
    console.log('Modal element:', deleteConfirmationModal);
    console.log('Confirm delete button:', btnConfirmDelete);
    console.log('Paket Pengujian Name Span in modal:', modalTestPackageNameSpan);
    console.log('Paket Pengujian ID Span in modal:', modalTestPackageIdSpan);
    // --- AKHIR DEBUGGING AWAL ---

    if (!deleteConfirmationModal || !btnConfirmDelete || !modalTestPackageNameSpan || !modalTestPackageIdSpan) {
        console.error('ERROR: Salah satu elemen modal konfirmasi hapus untuk Paket Pengujian tidak ditemukan. Pastikan ID HTML sudah benar.');
        return;
    }

    let currentTestPackageIdToDelete = null;

    // Event listener saat modal akan ditampilkan (dipicu oleh Bootstrap secara otomatis)
    deleteConfirmationModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Tombol yang memicu modal (yaitu tombol "Hapus" di DataTables)
        currentTestPackageIdToDelete = button.getAttribute('data-id'); // Ambil ID paket dari tombol
        const alatBahanName = button.getAttribute('data-test-package-name'); // Ambil nama paket dari tombol

        // Isi konten modal dengan informasi paket
        modalTestPackageNameSpan.textContent = alatBahanName;
        modalTestPackageIdSpan.textContent = currentTestPackageIdToDelete;

        // Atur ID paket yang akan dihapus pada tombol konfirmasi di modal
        btnConfirmDelete.setAttribute('data-id', currentTestPackageIdToDelete);

        console.log('Modal dibuka untuk Paket ID:', currentTestPackageIdToDelete, 'Nama:', alatBahanName); // DEBUGGING
    });

    // Event listener saat tombol "Hapus Data" di dalam modal diklik
    btnConfirmDelete.addEventListener('click', function() {
        console.log('Tombol Hapus Data di dalam modal diklik!'); // DEBUGGING: Konfirmasi klik
        const id = this.getAttribute('data-id'); // Ambil ID dari tombol konfirmasi modal
        console.log('ID paket yang akan dihapus:', id); // DEBUGGING: Tampilkan ID
        console.log('CSRF Token yang akan dikirim:', window.routes.csrfToken); // DEBUGGING: Pastikan token ada

        fetch(window.routes.deleteAlatBahan + '/' + id, {
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
                    throw new Error(errorData.message || 'Gagal menghapus data Paket Pengujian.');
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
            alert(data.message || 'Data Paket Pengujian berhasil dihapus.');
            // Reload DataTables
            if (window.alatBahanTable) {
                window.alatBahanTable.ajax.reload(null, false); // `false` agar tidak kembali ke halaman 1
            }
        })
        .catch(error => {
            // Sembunyikan modal jika ada error juga
            const modalInstance = bootstrap.Modal.getInstance(deleteConfirmationModal);
            if (modalInstance) {
                modalInstance.hide();
            }
            alert('Error: ' + error.message);
            console.error('Error deleting Paket Pengujian:', error);
        });
    });
});
