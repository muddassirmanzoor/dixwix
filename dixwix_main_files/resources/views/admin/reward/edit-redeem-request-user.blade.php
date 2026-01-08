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

    <form class="main-form" name="edit-plan-form" enctype="multipart/form-data" id="edit-redeem-form" method="post" action="{{ route('update-redeem-requests-user', $transaction->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Coins</label>
            <input type="number" class="form-control" id="coins" name="coins" value="{{ old('coins', $transaction->coins) }}" placeholder="coins">
            @error('coins')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Amount</label>
            <input type="text" class="form-control" id="price" name="price" value="{{ old('amount', $transaction->amount) }}" placeholder="Amount">
            @error('amount')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <button type="submit" class="btn lastbtn submit_btn">Update</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('edit-redeem-form');
        const coinsInput = document.getElementById('coins');
        const amountInput = document.getElementById('price');

        // Real-time conversion: 100 coins = 1 dollar
        coinsInput.addEventListener('input', function () {
            const coins = parseFloat(coinsInput.value) || 0;
            const amount = coins / 100;
            amountInput.value = amount.toFixed(2);
        });

        // Optional: status validation (if you later add a status dropdown)
        const statusSelect = form.querySelector('select[name="status"]');
        const existingStatus = "{{ $transaction->status }}";

        if (statusSelect) {
            form.addEventListener('submit', function (e) {
                if (statusSelect.value === existingStatus) {
                    alert("You have not changed the status!");
                    e.preventDefault(); // Prevent form submission
                }
            });
        }
    });
</script>
