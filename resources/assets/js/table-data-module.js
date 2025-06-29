document.addEventListener('DOMContentLoaded', function () {
    // A. LOGIKA UNTUK MENAMPILKAN DATA DI DATATABLES
    let columns = [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'code', name: 'code' },
        { data: 'name', name: 'name' },
        { data: 'description', name: 'description' },
        { data: 'details', name: 'details' }
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

    // Validasi elemen DOM
    if (!deleteConfirmationModal || !btnConfirmDelete || !modalModuleNameSpan || !modalModuleIdSpan) {
        console.error('ERROR: Salah satu elemen modal konfirmasi tidak ditemukan.');
        return;
    }

    let currentModuleIdToDelete = null;

    // Saat modal akan ditampilkan
    deleteConfirmationModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        currentModuleIdToDelete = button.getAttribute('data-id');
        const moduleName = button.getAttribute('data-module-name');

        modalModuleNameSpan.textContent = moduleName;
        modalModuleIdSpan.textContent = currentModuleIdToDelete;
        btnConfirmDelete.setAttribute('data-id', currentModuleIdToDelete);
    });

    // Saat tombol konfirmasi di klik
    btnConfirmDelete.addEventListener('click', function () {
        const id = this.getAttribute('data-id');

        fetch(window.routes.deleteModule + '/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': window.routes.csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(errorData => {
                        throw new Error(errorData.message || 'Gagal menghapus data Module.');
                    });
                }
                return res.json();
            })
            .then(data => {
                const modalInstance = bootstrap.Modal.getInstance(deleteConfirmationModal);
                if (modalInstance) modalInstance.hide();

                alert(data.message || 'Data Module berhasil dihapus.');
                if (window.moduleTable) {
                    window.moduleTable.ajax.reload(null, false);
                }
            })
            .catch(error => {
                const modalInstance = bootstrap.Modal.getInstance(deleteConfirmationModal);
                if (modalInstance) modalInstance.hide();

                alert('Error: ' + error.message);
                console.error('Error deleting Module:', error);
            });
    });
    $(document).on('click', '.btn-delete-module', function () {
        const moduleId = $(this).data('id');
        const moduleName = $(this).data('module-name');
    
        // Isi konten di modal
        $('#modalModuleId').text(moduleId);
        $('#modalModuleName').text(moduleName);
        $('#btnConfirmDelete').attr('data-id', moduleId);
    
        // Tampilkan modal
        const modalInstance = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
        modalInstance.show();
    });
    
});
