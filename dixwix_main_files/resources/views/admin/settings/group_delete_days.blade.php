<div class="container">
    <div class="heading">
        <h2>{{ $data['title'] }}</h2>
    </div>
    <div class="divider">
        <hr>
    </div>

    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif

    <div class="alert alert-info">
        This controls how many days a <strong>non-admin</strong> must wait after requesting group deletion.
        Admins can delete groups immediately.
    </div>

    <form method="post" action="{{ route('settings.group-delete.update') }}" class="main-form">
        @csrf

        <div class="form-group">
            <label for="group_delete_days">Group delete delay (days)</label>
            <input
                type="number"
                min="0"
                max="3650"
                class="form-control"
                id="group_delete_days"
                name="group_delete_days"
                value="{{ old('group_delete_days', $deleteDays ?? 90) }}"
                required
            />
            @error('group_delete_days')
                <div class="error_msg">*{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <button type="submit" class="btn lastbtn submit_btn">Save</button>
        </div>
    </form>
</div>

