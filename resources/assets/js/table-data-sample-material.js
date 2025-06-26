document.addEventListener('DOMContentLoaded', function () {
    // A. LOGIKA UNTUK MENAMPILKAN DATA DI DATATABLES
    let columns = [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'nama_sampel_material', name: 'nama_sampel_material' },
        { data: 'jumlah_sampel', name: 'jumlah_sampel' },
        {
            data: 'tanggal_penerimaan',
            name: 'tanggal_penerimaan',
            render: function(data) {
                // Memformat tanggal penerimaan menggunakan moment.js
                // Jika data null atau kosong, kembalikan string kosong
                return data ? moment(data).format('DD MMMM YYYY') : '';
            }
        },
        {
            data: 'tanggal_pengembalian',
            name: 'tanggal_pengembalian',
            render: function(data) {
                // Memformat tanggal pengembalian menggunakan moment.js
                // Jika data null atau kosong, kembalikan string kosong
                return data ? moment(data).format('DD MMMM YYYY') : '';
            }
        },
        { data: 'status_data', name: 'status_data' }
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
    window.sampleMaterialTable = $('.datatables-ajax').DataTable({
        processing: true,
        serverSide: true,
        ajax: window.routes.getSampelMaterialData, // Mengambil URL dari window.routes
        columns: columns
    });

    // B. LOGIKA UNTUK MODAL KONFIRMASI HAPUS
    const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
    const btnConfirmDelete = document.getElementById('btnConfirmDelete');
    const modalSampleNameSpan = document.getElementById('modalSampleName');
    const modalSampleIdSpan = document.getElementById('modalSampleId');

    // --- DEBUGGING AWAL: Memastikan semua elemen ditemukan ---
    console.log('Modal element:', deleteConfirmationModal);
    console.log('Confirm delete button:', btnConfirmDelete);
    console.log('Sample Name Span in modal:', modalSampleNameSpan);
    console.log('Sample ID Span in modal:', modalSampleIdSpan);
    // --- AKHIR DEBUGGING AWAL ---

    if (!deleteConfirmationModal || !btnConfirmDelete || !modalSampleNameSpan || !modalSampleIdSpan) {
        console.error('ERROR: Salah satu elemen modal konfirmasi hapus untuk Sampel Material tidak ditemukan. Pastikan ID HTML sudah benar.');
        return; // Hentikan eksekusi jika elemen penting tidak ditemukan
    }

    let currentSampleIdToDelete = null; // Variabel untuk menyimpan ID sampel material yang akan dihapus

    // Event listener saat modal akan ditampilkan (dipicu oleh Bootstrap secara otomatis)
    deleteConfirmationModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Tombol yang memicu modal (yaitu tombol "Hapus" di DataTables)
        currentSampleIdToDelete = button.getAttribute('data-id'); // Ambil ID sampel dari tombol
        const sampleName = button.getAttribute('data-sample-name'); // Ambil nama sampel dari tombol

        // Isi konten modal dengan informasi sampel material
        modalSampleNameSpan.textContent = sampleName;
        modalSampleIdSpan.textContent = currentSampleIdToDelete;

        // Atur ID sampel yang akan dihapus pada tombol konfirmasi di modal
        btnConfirmDelete.setAttribute('data-id', currentSampleIdToDelete);

        console.log('Modal dibuka untuk Sampel ID:', currentSampleIdToDelete, 'Nama:', sampleName); // DEBUGGING
    });

    // Event listener saat tombol "Hapus Data" di dalam modal diklik
    btnConfirmDelete.addEventListener('click', function() {
        console.log('Tombol Hapus Data di dalam modal diklik!'); // DEBUGGING: Konfirmasi klik
        const id = this.getAttribute('data-id'); // Ambil ID dari tombol konfirmasi modal
        console.log('ID sampel yang akan dihapus:', id); // DEBUGGING: Tampilkan ID
        console.log('CSRF Token yang akan dikirim:', window.routes.csrfToken); // DEBUGGING: Pastikan token ada

        fetch(window.routes.deleteSampelMaterial + '/' + id, {
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
                    throw new Error(errorData.message || 'Gagal menghapus data sampel & material.');
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
            alert(data.message || 'Data sampel & material berhasil dihapus.');
            // Reload DataTables
            if (window.sampleMaterialTable) {
                window.sampleMaterialTable.ajax.reload(null, false); // `false` agar tidak kembali ke halaman 1
            }
        })
        .catch(error => {
            // Sembunyikan modal jika ada error juga
            const modalInstance = bootstrap.Modal.getInstance(deleteConfirmationModal);
            if (modalInstance) {
                modalInstance.hide();
            }
            alert('Error: ' + error.message);
            console.error('Error deleting sampel material:', error);
        });
    });
});
