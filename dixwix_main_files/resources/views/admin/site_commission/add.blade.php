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

    <form class="main-form" name="add-type-form" enctype="multipart/form-data" id="add-type-form" method="post" action="{{route('store-commission')}}">
        @csrf
        <input type="hidden" name="mode" value="{{ $mode }}">

     
        <div class="form-group">
            <input type="number" required class="form-control" id="commission" name="commission" value="{{ (isset($commission) ? $commission["commission"] : "") }}" placeholder="Site Commission">
            @if (isset($errs['commission']))
            <div class="error_msg">*{{ $errs['commission'] }}</div>
            @endif
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn lastbtn submit_btn">Submit</button>
        </div>
        @if (isset($err_message))
        <div class="error_msg">*{{ $err_message }}</div>
        @endif
    </form>
</div>

    <div class="container bg-grey">
        <h3>Current Commission</h3>

        @if($commission)
            <h4><strong>Commission:</strong> {{ $commission->commission }}%</h4>
        @else
            <p>No commission set yet.</p>
        @endif
    </div>

