var dt_ajax = $('.datatables-ajax').DataTable({
  processing: true,
  serverSide: true,
  ajax: '/datatable/user',
  columns: [
    // { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
    { data: 'id', name: 'id' },
    { data: 'name', name: 'name' },
    { data: 'email', name: 'email' },
    { data: 'phone', name: 'phone' },
    { data: 'institution', name: 'institution' },
    { data: 'role', name: 'role' },
    { data: 'coordinator_name', name: 'coordinator_name' },
    { data: 'is_active', name: 'is_active' },
    { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
  ]
});