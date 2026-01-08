<style>
    #users-table_wrapper {
        overflow-x: auto;
    }
    table.dataTable th,
    table.dataTable td {
        white-space: nowrap;
    }
</style>

<div class="inner_content_table_actions d-flex flex-column flex-md-row mt-4 justify-content-between align-items-center">
    <h4 class="inner_content_title">{{ $data['title'] }}</h4>
    <div>
        <button id="delete-selected-btn" class="btn btn-danger" disabled>Delete Selected</button>
    </div>
</div>

<table id="users-table" class="display nowrap" style="width:100%">
    <thead>
        <tr>
            <th><input type="checkbox" id="select-all" /></th>
            <th>Name</th>
            <th>Email</th>
            <th>Last Update</th>
            <th>Dixwix Points</th>
            <th>Stripe Balance</th>
            <th>Phone</th>
            <th>Created Groups</th>
            <th>Joined Groups</th>
            <th>Date Added</th>
            <th>Roles</th>
            <th>Action</th>
        </tr>
    </thead>
</table>

<!-- Styles -->
<link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="//cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css" />

<!-- Responsive CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" />

<!-- Responsive JS -->
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>

<script>
$(document).ready(function () {
    const table = $('#users-table').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        responsive: true, // ✅ enables responsive plugin
        lengthChange: true,
        pageLength: 10,
        info: true,
        autoWidth: false,
        processing: true,
        serverSide: true,
        ajax: "{{ route('all-users') }}",
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'print',
                text: 'Print',
                exportOptions: {
                    columns: ':visible:not(:first-child):not(:last-child)',
                    modifier: { selected: true }
                }
            },
            {
                extend: 'excelHtml5',
                text: 'Excel',
                exportOptions: {
                    columns: ':visible:not(:first-child):not(:last-child)',
                    modifier: { selected: true }
                }
            },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                exportOptions: {
                    columns: ':visible:not(:first-child):not(:last-child)',
                    modifier: { selected: true }
                }
            }
        ],
        select: {
            style: 'multi',
            selector: 'td:first-child'
        },
        columnDefs: [
            {
                orderable: false,
                className: 'select-checkbox',
                targets: 0
            },
            {
                orderable: false,
                searchable: false,
                targets: -1
            }
        ],
        order: [[1, 'asc']],
        columns: [
            {
                data: null,
                defaultContent: '',
                className: 'select-checkbox',
                orderable: false
            },
            { data: 'name' },
            { data: 'email' },
            { data: 'last_update' },
            { data: 'dixwix_points' },
            { data: 'stripe_balance' },
            { data: 'phone' },
            { data: 'created_groups' },
            { data: 'joined_groups' },
            { data: 'date_added' },
            { data: 'roles' },
            { data: 'action' }
        ]
    });

    // Select/Deselect All Checkbox
    $('#select-all').on('click', function () {
        if (this.checked) {
            table.rows({ page: 'current' }).select();
        } else {
            table.rows({ page: 'current' }).deselect();
        }
    });

    // Update select-all checkbox state
    table.on('select deselect', function () {
        const allRows = table.rows({ page: 'current' }).nodes();
        const selectedRows = table.rows({ selected: true, page: 'current' }).nodes();
        const allChecked = selectedRows.length === allRows.length;
        $('#select-all').prop('checked', allChecked);

        // Enable/Disable Delete Button
        $('#delete-selected-btn').prop('disabled', selectedRows.length === 0);
    });

    // DELETE selected users
    $('#delete-selected-btn').on('click', function () {
        const selectedData = table.rows({ selected: true }).data().toArray();
        const ids = selectedData.map(row => row.id);

        if (!ids.length) return;

        Swal.fire({
            title: "Are you sure?",
            text: `You are about to delete ${ids.length} users.`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete them!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('/users/delete-multiple') }}",
                    method: 'POST',
                    data: {
                        ids: ids,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Deleted!', 'Users deleted successfully.', 'success');
                            table.ajax.reload();
                        } else {
                            Swal.fire('Error!', response.message || 'Something went wrong.', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error!', 'Request failed.', 'error');
                    }
                });
            }
        });
    });

    // Single row delete
    $('#users-table').on('click', '.delete-user-btn', function () {
        const userId = $(this).data('id');
        const userName = $(this).data('name');

        Swal.fire({
            title: "Are you sure?",
            text: `Delete user "${userName}" permanently?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('/users/delete-user') }}",
                    method: 'POST',
                    data: {
                        id: userId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Deleted!', `User "${userName}" deleted.`, 'success');
                            table.ajax.reload();
                        } else {
                            Swal.fire('Error!', response.message || 'Delete failed.', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error!', 'Delete request failed.', 'error');
                    }
                });
            }
        });
    });
});
</script>
