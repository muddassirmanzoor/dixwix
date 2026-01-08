<div class="container">
    <div class="heading">
        <h2>{{ $data['title'] }}</h2>
    </div>
    @if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
    @endif
    <div class="divider">
        <hr>
    </div>
   <form class="main-form" name="edit-plan-form" enctype="multipart/form-data" id="edit-plan-form" method="post" action="{{ route('update-plan', $plan->id) }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Name</label>
        <input type="text" required class="form-control" name="name" value="{{ old('name', $plan->name) }}">
    </div>

    

    <div class="form-group">
        <label>Allowed Items</label>
        <input type="number" class="form-control" name="allowed_items" value="{{ old('allowed_items', $plan->allowed_items) }}">
    </div>

    <div class="form-group">
        <label>Price</label>
        <input type="text" class="form-control" name="price" value="{{ old('price', $plan->price) }}">
    </div>

    

    <div class="form-group">
        <label>Fixed Categories</label>
        <input type="text" class="form-control" name="FixedCategories" value="{{ old('FixedCategories', $plan->FixedCategories) }}">
    </div>

    <div class="form-group">
        <label>Lend / Borrow included</label>
        <select class="form-control" name="LendBorrowincluded">
            <option value="1" {{ $plan->LendBorrowincluded ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ !$plan->LendBorrowincluded ? 'selected' : '' }}>No</option>
        </select>
    </div>

    <div class="form-group">
        <label>QR Codes Included</label>
        <select class="form-control" name="qr">
            <option value="1" {{ $plan->qr ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ !$plan->qr ? 'selected' : '' }}>No</option>
        </select>
    </div>

    <div class="form-group">
        <label>Rewards Included</label>
        <select class="form-control" name="reward">
            <option value="1" {{ $plan->reward ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ !$plan->reward ? 'selected' : '' }}>No</option>
        </select>
    </div>

    <div class="form-group">
        <label>Google SSO included</label>
        <select class="form-control" name="google">
            <option value="1" {{ $plan->google ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ !$plan->google ? 'selected' : '' }}>No</option>
        </select>
    </div>

    <div class="form-group">
        <label>Notifications</label>
        <select class="form-control" name="notification">
            <option value="1" {{ $plan->notification ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ !$plan->notification ? 'selected' : '' }}>No</option>
        </select>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>

</div>
