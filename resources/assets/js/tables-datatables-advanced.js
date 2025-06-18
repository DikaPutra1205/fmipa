var dt_ajax = $('.datatables-ajax').DataTable({
  processing: true,
  serverSide: true,
  ajax: '/datatable/institusi',
  columns: [
    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
    { data: 'nama_institusi', name: 'nama_institusi' },
    { data: 'nama_koordinator', name: 'nama_koordinator' },
    { data: 'telp_wa', name: 'telp_wa' },
    { data: 'status', name: 'status' },
    { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
  ]
});