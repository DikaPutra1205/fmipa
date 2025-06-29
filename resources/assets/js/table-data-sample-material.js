'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const ajaxTable = $('.datatables-ajax');
    if (!ajaxTable.length) return;

    const dt_ajax = ajaxTable.DataTable({
        processing: true,
        serverSide: true,
        ajax: window.routes.getSampelData,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'test_id', name: 'test.id' },
            { data: 'mitra_name', name: 'test.mitra.name' },
            { data: 'nama_sampel_material', name: 'nama_sampel_material' },
            { data: 'jumlah_sampel', name: 'jumlah_sampel' },
            { data: 'tanggal_penerimaan', name: 'tanggal_penerimaan' },
            { data: 'status', name: 'status' },
            { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
        ]
    });

    // Logika untuk modal hapus (tetap sama)
    const deleteModal = document.getElementById('deleteConfirmationModal');
    const confirmDeleteBtn = document.getElementById('btnConfirmDelete');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('modalSampleName').textContent = button.getAttribute('data-sample-name');
        confirmDeleteBtn.setAttribute('data-id', button.getAttribute('data-id'));
    });
    confirmDeleteBtn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        fetch(window.routes.deleteSampel + '/' + id, {
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': window.routes.csrfToken, 'Accept': 'application/json'}
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(deleteModal).hide();
                dt_ajax.ajax.reload();
            }
        });
    });
});
