<div class="inner_content_table_actions d-flex flex-column flex-md-row mt-4">
    <h4 class="inner_content_title">{{ $data['title'] }}</h4>
</div>

@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif

<div class="table-responsive">
    <table id="items_table" class="table">
        <thead>
            <tr>
                <th scope="col">Select</th> {{-- Checkbox column --}}
                <th scope="col">User</th>
                <th scope="col">Coins</th>
                <th scope="col">Amount</th>
                <th scope="col">Commission Amount</th>
                <th scope="col">Stripe Amount</th>
                <th scope="col">Approved By</th>
                <th scope="col">Approved At</th>
                <th scope="col">Status</th>
                <th scope="col" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>
                    <input type="checkbox" class="row-checkbox" value="{{ $transaction->id }}">
                </td>
                <td>{{ $transaction?->user?->name }}</td>
                <td>{{ $transaction->coins }}</td>
                <td>{{ $transaction->amount }}</td>
                <td>{{ $transaction->system_fee }}</td>
                <td>USD {{ $transaction?->user?->reward_balance / 100 }}</td>
                <td>{{ $transaction->approveUser->name ?? "-" }}</td>
                <td>{{ $transaction->approved_at ?? "-" }}</td>
                <td>{{ $transaction->status_text ?? "-" }}</td>
                <td>
                    <div class="d-flex justify-content-center">
                        <a href="{{ $transaction->status == \App\Models\RewardTransaction::APPROVED ? 'javascript:void(0);' : route('edit-redeem-requests', ['id' => $transaction->id]) }}"
                           style="{{ $transaction->status == 1 ? 'cursor: not-allowed; opacity: 0.7;' : '' }}">
                            <img src="{{ url("assets/media/edit-orange.png") }}" width="15px" height="15px" />
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<!-- DataTables CSS -->
<link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" />
<!-- Buttons Extension CSS -->
<link rel="stylesheet" href="//cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />

<!-- jQuery -->
<script src="//code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<!-- Buttons Extension JS -->
<script src="//cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<!-- JSZip for Excel export -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<!-- PDFMake for PDF export -->
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<!-- Buttons for CSV, Excel, and PDF -->
<script src="//cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="//cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script>
    $(document).ready(function() {
        $('#items_table').DataTable({
            paging: true
            , searching: true
            , ordering: true
            , responsive: true
            , lengthChange: true
            , pageLength: 10
            , info: true
            , autoWidth: false
            , order: [[0, 'desc']]
            , columnDefs: [{
                orderable: false
                , targets: -1
            }]
            , language: {
                search: "Search:"
                , lengthMenu: "Show _MENU_ entries"
                , info: "Showing _START_ to _END_ of _TOTAL_ entries"
                , paginate: {
                    first: "First"
                    , last: "Last"
                    , next: "Next"
                    , previous: "Previous"
                }
            }
            , dom: 'Bfrtip', // Add the Buttons extension
            buttons: [
                {
                    extend: 'csvHtml5',
                    text: 'Export CSV',
                    title: 'Data Export',
                    exportOptions: {
                        rows: function (idx, data, node) {
                            return $(node).find('.row-checkbox').is(':checked');
                        }
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: 'Export Excel',
                    title: 'Data Export',
                    exportOptions: {
                        rows: function (idx, data, node) {
                            return $(node).find('.row-checkbox').is(':checked');
                        }
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: 'Export PDF',
                    title: 'Data Export',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        rows: function (idx, data, node) {
                            return $(node).find('.row-checkbox').is(':checked');
                        }
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: 'Data Export',
                    exportOptions: {
                        rows: function (idx, data, node) {
                            return $(node).find('.row-checkbox').is(':checked');
                        }
                    }
                }
            ]
        });
    });

</script>
