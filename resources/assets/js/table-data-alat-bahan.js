document.addEventListener('DOMContentLoaded', function () {
    // A. LOGIKA UNTUK MENAMPILKAN DATA DI DATATABLES
    let columns = [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'nama_alat_bahan', name: 'nama_alat_bahan' }, // Nama kolom dari model AlatBahan
        { data: 'kondisi_alat', name: 'kondisi_alat' },     // Nama kolom dari model AlatBahan
        { data: 'jumlah_alat', name: 'jumlah_alat' },       // Nama kolom dari model AlatBahan
        { data: 'status_data', name: 'status_data' }       // Nama kolom dari model AlatBahan
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
        ajax: window.routes.getAlatBahanData, // Mengambil URL dari window.routes
        columns: columns
    });

    // B. LOGIKA UNTUK MODAL KONFIRMASI HAPUS
    const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
    const btnConfirmDelete = document.getElementById('btnConfirmDelete');
    const modalAlatBahanNameSpan = document.getElementById('modalAlatBahanName');
    const modalAlatBahanIdSpan = document.getElementById('modalAlatBahanId');

    // --- DEBUGGING AWAL: Memastikan semua elemen ditemukan ---
    console.log('Modal element:', deleteConfirmationModal);
    console.log('Confirm delete button:', btnConfirmDelete);
    console.log('Alat Bahan Name Span in modal:', modalAlatBahanNameSpan);
    console.log('Alat Bahan ID Span in modal:', modalAlatBahanIdSpan);
    // --- AKHIR DEBUGGING AWAL ---

    if (!deleteConfirmationModal || !btnConfirmDelete || !modalAlatBahanNameSpan || !modalAlatBahanIdSpan) {
        console.error('ERROR: Salah satu elemen modal konfirmasi hapus untuk Alat & Bahan tidak ditemukan. Pastikan ID HTML sudah benar.');
        return; // Hentikan eksekusi jika elemen penting tidak ditemukan
    }

    let currentAlatBahanIdToDelete = null; // Variabel untuk menyimpan ID alat/bahan yang akan dihapus

    // Event listener saat modal akan ditampilkan (dipicu oleh Bootstrap secara otomatis)
    deleteConfirmationModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Tombol yang memicu modal (yaitu tombol "Hapus" di DataTables)
        currentAlatBahanIdToDelete = button.getAttribute('data-id'); // Ambil ID alat/bahan dari tombol
        const alatBahanName = button.getAttribute('data-alat-bahan-name'); // Ambil nama alat/bahan dari tombol

        // Isi konten modal dengan informasi alat/bahan
        modalAlatBahanNameSpan.textContent = alatBahanName;
        modalAlatBahanIdSpan.textContent = currentAlatBahanIdToDelete;

        // Atur ID alat/bahan yang akan dihapus pada tombol konfirmasi di modal
        btnConfirmDelete.setAttribute('data-id', currentAlatBahanIdToDelete);

        console.log('Modal dibuka untuk Alat/Bahan ID:', currentAlatBahanIdToDelete, 'Nama:', alatBahanName); // DEBUGGING
    });

    // Event listener saat tombol "Hapus Data" di dalam modal diklik
    btnConfirmDelete.addEventListener('click', function() {
        console.log('Tombol Hapus Data di dalam modal diklik!'); // DEBUGGING: Konfirmasi klik
        const id = this.getAttribute('data-id'); // Ambil ID dari tombol konfirmasi modal
        console.log('ID alat/bahan yang akan dihapus:', id); // DEBUGGING: Tampilkan ID
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
                    throw new Error(errorData.message || 'Gagal menghapus data Alat & Bahan.');
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
            alert(data.message || 'Data Alat & Bahan berhasil dihapus.');
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
            console.error('Error deleting Alat & Bahan:', error);
        });
    });
});
