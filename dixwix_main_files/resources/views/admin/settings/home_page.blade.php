<?php if(isset($retdata)){extract($retdata);} ?>
<div class="container">
    <div class="heading">
        <h2>{{ $data["title"] }}</h2>
    </div>
    @if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
    @endif
    <div class="divider">
        <hr>
    </div>

    <div class="table-responsive">
    <table id="items_table1" class="table data-table-format table-bordered table-hover">
        <thead class="bg-head">
            <tr>
                <th>Image</th>
                <th>Book Title</th>
                <th>Created At</th>
                <th>Writer</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['books'] as $book)
                <tr>
                    <td>
                        <img src="{{ asset($book->cover_page) }}" alt="Cover" width="50" height="50">
                    </td>
                    <td>{{ $book->name }}</td>
                    <td>{{ $book->created_at->format('d-m-Y') }}</td>
                    <td>{{ $book->writers }}</td>
                    <td>
                        @if ($book->status == 1)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <form action="{{ route('books.toggleStatus', $book->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm {{ $book->is_on_desktop == 1 ? 'btn-success' : 'btn-danger' }}">
                                {{ $book->is_on_desktop == 1 ? 'on' : 'off' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination Links -->
<div class="mt-3">
    {{ $data['books']->links() }}
</div>

</div>
