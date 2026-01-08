<!-- {{--<a href="javascript:void(0);" onclick="javascript:loadHistoryReport({!! $id !!})">Just Click It</a>--}} -->
<div class="table-group-details table-responsive loading-data-ajax">
    <table id="items_table_requests" class="table table-striped table-rounded">
        <thead>
            <tr>
                <th scope="col" style="background:#094042;">Thumbnail </th>  
                <th scope="col" style="background:#094042;">Item Name</th>
                <th scope="col" style="background:#094042;">Image At Return</th>
                @if ($group["created_by"] == auth()->id())
                    <th scope="col" style="background:#094042;">Actions</th>
              
                @endif
                <th scope="col" style="background:#094042;">In Original Condition</th>
                <th scope="col" style="background:#094042;">Rental ID</th>
                <th scope="col" style="background:#094042;">Rent Commission</th>
                <th scope="col" style="background:#094042;">Requested By</th>
                <th scope="col" style="background:#094042;">Trust Scores</th>
                <th scope="col" style="background:#094042;">Due Date</th>
                <th scope="col" style="background:#094042;">Requested Date</th>
                <th scope="col" style="background:#094042;">Type </th>
                
             
            </tr>
        </thead>
        <tbody>
            {{-- Loop through normal return requests --}}
@foreach($group['returnRequests'] as $entry)
    @php
        // Get the user by ID and handle case where user might not exist
        $user = \App\Models\User::find($entry['reserved_by']);
        $requestedBy = $user ? $user->name : 'Unknown User';

        // Get the book and handle case where book might not exist
        $book = $entry['book'] ?? null;
    @endphp
    @if($book && ($book['group_id'] == $group['id'])) {{-- Only display the row if the book exists --}}
        <tr>
            <td>
                <img style="width: 100px; height:100px" src="{{ $book['cover_page'] }}" alt="Cover Page">
            </td>
            <td>{{ $book['name'] }}</td>
            <td>
                @if($entry['state'] === 'return-request')
                    <a href="{{ $entry['image_at_returning'] }}" target="_blank">
                        <img style="width: 100px; height:100px" src="{{ $entry['image_at_returning'] }}" alt="Image">
                    </a>
                @else
                    <span>Pending</span>
                @endif
            </td>
            @if ($group["created_by"] == auth()->id())
            <td>
                @if($entry['state'] == 'rejected')
                    <span class="badge bg-danger text-white">Rejected</span>
                @elseif($entry['is_reserved'] == '2' && $entry['reserved_by'] != auth()->id())
                    <div class="btn-group">
                        <a href="javascript:void(0)" onclick="approveDisapprove('approve', {{ $entry['id'] }}, {{ $entry['book']['id'] }}, this)" class="btn text-nowrap btn-success">Approve</a> &nbsp;
                        <a href="javascript:void(0)" onclick="approveDisapprove('disapprove', {{ $entry['id'] }}, {{ $entry['book']['id'] }}, this)" class="btn text-nowrap btn-danger">Reject</a>
                    </div>
                @elseif($entry['state'] === 'return-request' && $entry['reserved_by'] != auth()->id())
                    <div class="btn-group">
                        <a href="javascript:void(0)" onclick="adminReturnBook({{ $entry['id'] }}, {{ $entry['book_id'] }}, event)" class="btn text-nowrap btn-success">Approve</a>  &nbsp;
                        <a href="javascript:void(0)" onclick="showSwalMessageWithCallback('Confirmation','Are you sure you want to reject the return request?','question',function(){rejectReturnRequest({{ $entry['id'] }})})" class="btn text-nowrap btn-danger">Reject</a>
                    </div>
                @else
                    <span>Return Pending</span>
                @endif
            </td>
            @endif
            <td>
                <span class="badge badge-{{ $entry['original_condition'] == 'yes' ? 'success' : 'warning' }}">{{ $entry['original_condition'] }}</span>
            </td>
            <td>{{ $book['item_id'] }}</td>
            <td>{{ $entry['rent_commission']. ' USD' }}</td>
            <td>{{ $requestedBy }}</td>
            <td>{{ $entry['average_rating'] ?? 'N/A' }}</td>
            <td>
                @php
                    $dueDate = \Carbon\Carbon::parse($entry['due_date']);
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
            <td>{{ \Carbon\Carbon::parse($entry['reserved_at'])->format('F j, Y') }}</td>

            <td>
                {{
                    ($entry['is_reserved'] == 2) ? 'Rental' :
                    ($entry['state'] == 'return-request' ? 'Return' : 'Not Available')
                }}
            </td>
            
        </tr>
    @endif
    @endforeach

    {{-- Loop through rejected items --}}
    @foreach($group['itemMetrics']['rejected_items'] as $rejectedItem)
    @php
    $book = \App\Models\Book::find($rejectedItem['book_id']);
    $user = \App\Models\User::find($rejectedItem['disapproved_by']);
    @endphp
    <tr style="background-color: #f8d7da;">
        <td>
            <img style="width: 100px; height:100px" src="{{ $book->cover_page }}" alt="Cover Page">
        </td>
        <td>{{ $book->name }}</td>
        <td>
            <span>Image Not Available</span>
        </td>
        <td>
            <span class="badge badge-danger">Not Applicable</span>
        </td>
                <td>
            <span class="badge badge-danger">Not Applicable</span>
        </td>
        <td>{{ $book->item_id }}</td>
        <td>{{ "N/A" }}</td>
        <td>{{ $user?->name ?? 'Unknown' }}</td>
        <td>N/A</td>
        <td>
            <span class="badge bg-danger text-white">{{ \Carbon\Carbon::parse($rejectedItem['disapproved_at'])->format('d-m-Y') }}</span>
        </td>
        <td>
            <span class="badge bg-danger text-white">{{ \Carbon\Carbon::parse($rejectedItem['disapproved_at'])->format('d-m-Y') }}</span>
        </td>

        

        <td>
            <span class="badge bg-danger text-white">Rejected</span>
        </td>
    </tr>
    @endforeach



        </tbody>
    </table>
</div>


<script>
    function rejectReturnRequest(entry_id) {
        jQuery.ajax({
            type: 'POST'
            , url: '/reject-return-request'
            , data: {
                "_token": "<?=csrf_token()?>"
                , "entry_id": entry_id
            }
            , success: function(result) {
                window.location.reload();
            }
        });
    }

    function loadHistoryReport(id) {
        // Show loader before API call
        
        document.getElementById('refreshBtn').style.display = 'inline-block';
        
        jQuery(".loading-data-ajax").html('<div class="loader">Loading...</div>');

        jQuery.ajax({
            type: 'GET',
            url: '/history-logs-report/' + id,
            success: function(result) {
                jQuery(".loading-data-ajax").html(result.html);
            },
            error: function() {
                // Optionally handle error and remove loader
                jQuery(".loading-data-ajax").html('<div class="error">Failed to load report. Please try again.</div>');
            },
            complete: function() {
                // Optionally you can clear the loader here but success and error already handle content replacement
                // So this can be kept empty or just for any cleanup if needed
            }
        });
    }

</script>
