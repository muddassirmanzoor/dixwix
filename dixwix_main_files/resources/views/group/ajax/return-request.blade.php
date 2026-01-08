<div class="table-group-details table-responsive">
    <table id="items_table_requests" class="table table-striped table-rounded items_table">
        <thead>
            <tr>
                
          <th scope="col" style="background:#094042;">Thumbnail</th>  
                <th scope="col" style="background:#094042;">Item Name</th>
                <th scope="col" style="background:#094042;">Image At Return</th>
                <th scope="col" style="background:#094042;">In Original Condition</th>
                <th scope="col" style="background:#094042;">Rental ID</th>
                <th scope="col" style="background:#094042;">Requested By</th>
                <!-- <th scope="col" style="background:#094042;">Trust Scores</th> -->
                <th scope="col" style="background:#094042;">Due Date</th>
                <th scope="col" style="background:#094042;">Requested Date</th>
                <th scope="col" style="background:#094042;">Type</th>
            </tr>
        </thead>
        <tbody>


@foreach($history_log as $entry)

    @php
        $entry_book = \App\Models\Entries::where("book_id", $entry->book_id)
                        // ->where("reserved_by", $entry->user_id)
                        // ->where("group_id", $entry->group_id)
                        //->where("reserved_at", $entry->reserved_at)
                        //->where("due_date", $entry->due_date)
                        ->first();

                   //dd($entry_book,$entry->book_id,$entry->user_id,$entry->group_id);     
    @endphp

    @if($entry->book)
      l<tr>
            <td>
                <img style="width: 100px; height:100px" src="{{ $entry->book->cover_page }}" alt="Cover Page">
            </td>
            <td>{{ $entry->book->name }}</td>
            <td>
                @if( $entry->status === 'returned')
                    <a href="{{ $entry_book?->image_at_returning }}" target="_blank">
                        <img style="width: 100px; height:100px" src="{{ $entry_book?->image_at_returning }}" alt="Image">
                    </a>
                @else
                    <span>Pending</span>
                @endif
            </td>
            <td>
                <span class="badge badge-{{ isset($entry_book?->original_condition) && $entry_book?->original_condition == 'yes' ? 'success' : 'warning' }}">{{ isset($entry_book?->original_condition) && $entry_book?->original_condition }}</span>
            </td>
            <td>{{ $entry->book->item_id }}</td>
            <td>{{ $entry->user->name }}</td>
{{--            <td>{{ $entry['average_rating'] ?? 'N/A' }}</td>--}}
            <td>
                @php
                    $dueDate = \Carbon\Carbon::parse($entry->due_date);
                    $isOverdue = $dueDate->isPast(); // Check if the due date is in the past
                @endphp

                @if($isOverdue)
                    <span class="badge bg-danger text-white" style="font-size: 12px;">{{ $dueDate->format('F j, Y') }}</span>
                @else
                    {{ $dueDate->format('F j, Y') }}
                @endif

                @if($isOverdue)
                    <span class="badge bg-danger text-white">Overdue</span>
                @endif
            </td>
            <td>{{ \Carbon\Carbon::parse($entry->reserved_at)->format('F j, Y') }}</td>

            <td>
                {{
                    (isset($entry_book?->is_reserved) && $entry_book?->is_reserved == 2) ? 'Rental' :
                    (isset($entry_book?->state) && $entry_book?->state == 'return-request' ? 'Return Request' : 'Returned')
                }}
            </td>
        </tr>
    @endif
    @endforeach
        </tbody>
    </table>
</div>


<link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script language="JavaScript">
    $(document).ready(function() {
        /******** Pagination **********/
        $('.items_table').DataTable({
            paging: true
            , searching: true
            , ordering: true
            , responsive: true
            , lengthChange: true
            , pageLength: 10
            , info: true
            , autoWidth: false
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
        });
        /******** Pagination **********/
    });
</script>
