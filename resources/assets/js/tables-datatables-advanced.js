var dt_ajax = $('.datatables-ajax').DataTable({
  processing: true,
  serverSide: true,
  ajax: '/datatable/institusi',
  columns: [
    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
    { data: 'institution', name: 'institution' },
    { data: 'coordinator_name', name: 'coordinator_name' },
    { data: 'phone', name: 'phone' },
    { data: 'is_active', name: 'is_active' },
    { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
  ]
});