@extends('frontend.layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<div class="row justify-content-center">
    <div class="col">
        <div class="card p-4 shadow">
            <h2 class=" mb-3">Add daily Total sale</h2>
            <p class="mb-3">Add your total sale Manually</p>
            <form method="POST" action="{{ route('dailySales.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="date" class="form-label fw-bold">Date<span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="date" required value="{{date('Y-m-d')}}">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-bold">Description</label>
                    <textarea class="form-control" name="description" rows="3"></textarea>
                </div>

                <div class="mb-3 fw-bold">Split of Amount</div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="cardAmount" class="form-label">Card amount</label>
                        <input onchange="calculateTotal()" type="number" class="form-control" name="card_amount" id="card_amount" >
                    </div>
                    <div class="col-md-4">
                        <label for="cashAmount" class="form-label">Cash amount</label>
                        <input onchange="calculateTotal()" type="number" class="form-control" name="cash_amount" id="cash_amount" >
                    </div>
                    <div class="col-md-4">
                        <label for="othersAmount" class="form-label">Others amount</label>
                        <input onchange="calculateTotal()" type="number" class="form-control" name="others_amount" id="others_amount" >
                    </div>
                </div>

                <div class="mb-3">
                    <label for="totalAmount" class="form-label fw-bold">Total amount</label>
                    <input type="number" class="form-control" name="total_amount" id="total_amount" readonly>
                </div>

                <div class="mb-3">
                    <label for="person" class="form-label fw-bold">Assign person of work<span class="text-danger">*</span></label>
                    <select class="form-select js-example-basic-single" name="assigned_person_id[]" required multiple>
                        <option value="">--Select--</option>
                        @foreach ($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="add-customer-btns text-left">
                    <button type="submit" class="btn customer-btn-save">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        
        $('.js-example-basic-single').select2({
            
        });

        $('.js-example-basic-single-no-new-value').select2({
        });

        
    });

    function calculateTotal(){
        var card_amount = document.getElementById('card_amount');
        var cash_amount = document.getElementById('cash_amount');
        var others_amount = document.getElementById('others_amount');
        var total_amount = document.getElementById('total_amount');

        if((card_amount.value*1) < 0) card_amount.value = 0;
        if((cash_amount.value*1) < 0) cash_amount.value = 0;
        if((others_amount.value*1) < 0) others_amount.value = 0;

        total_amount.value = Math.max(0, (card_amount.value*1) + (cash_amount.value*1) + (others_amount.value*1));

    }
</script>

@endsection