<div class="container">
    <div class="heading">
        <h2>{{ $data['title'] }}</h2>
    </div>

    @if(session()->has('success'))
        <div class="alert alert-success">{{ session()->get('success') }}</div>
    @endif

    <div class="divider"><hr></div>

    <form class="main-form" method="POST" action="{{ route('plans.store') }}">
        @csrf

        <div class="form-group">
            <label>Name</label>
            <input type="text" required class="form-control" name="name" value="{{ old('name') }}" placeholder="Plan Name">
            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

       

        <div class="form-group">
            <label>Allowed Items</label>
            <input type="number" required class="form-control" name="allowed_items" value="{{ old('allowed_items') }}" placeholder="Allowed Items">
            @error('allowed_items') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label>Price</label>
            <input type="text" class="form-control" name="price" value="{{ old('price') }}" placeholder="Price (optional)">
            @error('price') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

       

        <div class="form-group">
            <label>Fixed Categories</label>
            <input type="text" class="form-control" name="FixedCategories" value="{{ old('FixedCategories') }}" placeholder="Fixed Categories">
            @error('FixedCategories') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label><input type="checkbox" name="LendBorrowincluded" value="1" {{ old('LendBorrowincluded') ? 'checked' : '' }}> Lend / Borrow Included</label>
        </div>

        <div class="form-group">
            <label><input type="checkbox" name="qr" value="1" {{ old('qr') ? 'checked' : '' }}> QR Codes Included</label>
        </div>

        <div class="form-group">
            <label><input type="checkbox" name="reward" value="1" {{ old('reward') ? 'checked' : '' }}> Rewards Included</label>
        </div>

        <div class="form-group">
            <label><input type="checkbox" name="google" value="1" {{ old('google') ? 'checked' : '' }}> Google SSO Included</label>
        </div>

        <div class="form-group">
            <label><input type="checkbox" name="notification" value="1" {{ old('notification') ? 'checked' : '' }}> Notifications</label>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Create Plan</button>
        </div>
    </form>
</div>
