

<div class="inner_content_table_actions d-flex flex-column flex-md-row justify-content-between mt-4">
    <h4 class="inner_content_title">{{ $data['title'] }}</h4>
    <a href="{{ URL::to('/plans/create') }}" class="btn btn-primary">Add New Plan</a>
</div>


<div class="table-responsive">
    <table id="items_table" class="table">
        <thead>
            <tr>
                <th scope="col">Name</th>
              
                <th scope="col">Allowed Items</th>
                <th scope="col">Price</th>
                <th scope="col">Fixed Categories</th>
                <th scope="col">Lend / Borrow included</th>
                <th scope="col">QR Codes Included</th>
                <th scope="col">Rewards Included</th>
                <th scope="col">Google SSO included</th>
                <th scope="col">Notifications</th>
                
                <th scope="col" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($plans as $plan)
<tr>
    <td>{{ $plan->name }}</td>
   
    <td>{{ $plan->allowed_items }}</td>
    <td>{{ $plan->price ?? 'Free' }}</td>
    <td>{{ $plan->FixedCategories }}</td>
    <td>{{ $plan->LendBorrowincluded ? 'true' : 'false' }}</td>
    <td>{{ $plan->qr ? 'true' : 'false' }}</td>
    <td>{{ $plan->reward ? 'true' : 'false' }}</td>
    <td>{{ $plan->google ? 'true' : 'false' }}</td>
    <td>{{ $plan->notification ? 'true' : 'false' }}</td>
  
    <td>
    <div class="d-flex justify-content-center gap-2">
        {{-- Edit Button --}}
        <a href="{{ route('edit-plan', ['id' => $plan->id]) }}" class="text-warning" title="Edit">
            <i class="fas fa-edit" style="font-size: 16px;"></i>
        </a>

        {{-- Delete Button --}}
        <form method="POST" style="    margin-left: 24px;
    margin-top: -4px;" action="{{ route('delete-plan', ['id' => $plan->id]) }}" onsubmit="return confirm('Are you sure you want to delete this plan?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-link p-0 m-0 text-danger" title="Delete">
                <i class="fas fa-trash-alt" style="font-size: 16px;"></i>
            </button>
        </form>
    </div>
</td>

</tr>
@endforeach

        </tbody>
    </table>
</div>

<link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" />
<script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
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
    });

</script>
