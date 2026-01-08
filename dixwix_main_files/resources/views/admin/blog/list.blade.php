<!-- <div class="inner_content_table_actions d-flex flex-column flex-md-row mt-4">
    <h4 class="inner_content_title">{{ $data['title'] }}</h4>
</div>

<div class="table-responsive">
    <table id="items_table" class="table">
        <thead>
            <tr>
                <th scope="col">Title</th>
                <th scope="col">view Post</th>
                <th scope="col">Image</th>
                <th scope="col">Status</th>
                <th scope="col">Created At</th>
                <th scope="col" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
            <tr>
                <td>{{ $post->title }}</td>
                <td>
                    <a target="_blank" href="{{ route('post-slug', $post->slug) }}">View Post</a>
                </td>
                <td class="p-0">
                    <img style="height:60px" src="{{ $post->image }}" alt="Post Image">
                </td>
                <td>
                    <span class="badge {{ $post->status == 'pending' ? 'bg-secondary text-white' : 'bg-success' }}">{{ $post->status }}</span>
                </td>

                <td>{{ date("m/d/Y", strtotime($post->created_at)) }}</td>

                <td>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('blog-post.edit', $post->id) }}">
                            <img src="{{ url('assets/media/edit-orange.png') }}" width="15px" height="15px" />
                        </a>
                        <button class="border-0 bg-transparent delete-post" onclick="deletepost('{{ $post->id }}')">
                            <img src="{{ url('assets/media/delete.png') }}" width="15px" height="15px" />
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div> -->

<div class="inner_content_table_actions d-flex flex-column flex-md-row mt-4 justify-content-between align-items-center">
    <h4 class="inner_content_title">{{ $data['title'] }}</h4>
    <button class="btn btn-danger btn-sm" id="delete-selected">Delete Selected</button>
</div>

<div class="table-responsive">
    <table id="items_table" class="table">
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all"></th> <!-- Master checkbox -->
                <th scope="col">Title</th>
                <th scope="col">View Post</th>
                <th scope="col">Image</th>
                <th scope="col">Status</th>
                <th scope="col">Created At</th>
                <th scope="col" class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
            <tr>
                <td><input type="checkbox" class="post-checkbox" value="{{ $post->id }}"></td>
                <td>{{ $post->title }}</td>
                <td>
                    <a target="_blank" href="{{ route('post-slug', $post->slug) }}">View Post</a>
                </td>
                <td class="p-0">
                    <img style="height:60px" src="{{ $post->image }}" alt="Post Image">
                </td>
                <td>
                    <span class="badge {{ $post->status == 'pending' ? 'bg-secondary text-white' : 'bg-success' }}">{{ $post->status }}</span>
                </td>
                <td>{{ date("m/d/Y", strtotime($post->created_at)) }}</td>
                <td>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('blog-post.edit', $post->id) }}">
                            <img src="{{ url('assets/media/edit-orange.png') }}" width="15px" height="15px" />
                        </a>
                        <button class="border-0 bg-transparent delete-post" onclick="deletepost('{{ $post->id }}')">
                            <img src="{{ url('assets/media/delete.png') }}" width="15px" height="15px" />
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function deletepost(postId) {
        Swal.fire({
            title: "Are You Sure?"
            , text: "Do you really want to permanently remove this post?"
            , icon: "warning"
            , showCancelButton: true
            , confirmButtonColor: "#3085d6"
            , cancelButtonColor: "#d33"
            , confirmButtonText: "Yes, Delete Permanently"
            , cancelButtonText: "Cancel"
            , reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/blog-post/${postId}`,
                    method: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    }
                    , success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Deleted!'
                                , text: `The post has been deleted.`
                                , icon: 'success'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!'
                                , text: response.message || 'Could not delete the post.'
                                , icon: 'error'
                            });
                        }
                    }
                    , error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error!'
                            , text: 'An unexpected error occurred.'
                            , icon: 'error'
                        });
                    }
                });
            }
        });
    }

    // Select/Deselect all checkboxes
    $('#select-all').on('change', function () {
        $('.post-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Delete selected posts
    $('#delete-selected').on('click', function () {
        let selected = $('.post-checkbox:checked').map(function () {
            return $(this).val();
        }).get();

        if (selected.length === 0) {
            Swal.fire('No Selection', 'Please select at least one post.', 'info');
            return;
        }

        Swal.fire({
            title: "Are You Sure?",
            text: `You are about to delete ${selected.length} post(s).`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Delete Permanently",
            cancelButtonText: "Cancel",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('blog-post.bulk-delete') }}", // You will create this route
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: selected
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Deleted!', 'Selected posts have been deleted.', 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error', response.message || 'Could not delete selected posts.', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'An unexpected error occurred.', 'error');
                    }
                });
            }
        });
    });

</script>
