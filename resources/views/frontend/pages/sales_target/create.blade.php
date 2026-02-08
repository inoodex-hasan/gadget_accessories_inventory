@extends('frontend.layouts.app') 
@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #888 transparent;
        border-width: 0 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #888 transparent transparent transparent;
        border-style: solid;
        border-width: 0 !important;
        height: 0;
        left: 50%;
        margin-left: -4px;
        margin-top: -2px;
        position: absolute;
        top: 50%;
        width: 0;
    }
</style>

<div class="content container-fluid">
    <div class="card mb-0">
        <div class="card-body">
            <!-- Page Header -->
            <div class="page-header">  
                <div class="content-page-header d-flex justify-content-between align-items-center">
                    <h5>Add Sales Target</h5>
                    <a class="btn btn-primary" href="{{route('salesTarget.index')}}">
                        Sales Target List</a>
                </div>
            </div>
            <!-- /Page Header -->
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('salesTarget.store') }}" method="post">
                        @csrf
                        <div class="form-group-item">  
                            <div class="row">  
                                <div class="col-lg-4 col-md-6 col-sm-12">  
                                    <div class="input-block mb-3">  
                                        <label>Select Month & Year <span class="text-danger">*</span></label>   
                                        <input type="month" name="date" class="form-control" value="{{ old('date') }}" required autocomplete="off">  
                                    </div>
                                </div> 
                                <div class="col-lg-4 col-md-6 col-sm-12">  
                                    <div class="input-block mb-3">  
                                        <label>Amount <span class="text-danger">*</span></label>  
                                        <input type="number" step="0.01" name="amount" class="form-control" placeholder="Enter Amount" value="{{ old('amount') }}" required autocomplete="off">  
                                    </div>  
                                </div>   
                            </div>  
                        </div>                
                        <div class="add-customer-btns text-left">
                            <button type="submit" class="btn customer-btn-save">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@endsection
