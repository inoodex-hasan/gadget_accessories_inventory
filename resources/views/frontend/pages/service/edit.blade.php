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
								<div class="content-page-header">
									<h5>Update Service</h5>
								</div>	
							</div>
							<!-- /Page Header -->				
							<div class="row">
								<div class="col-md-12">
									<form action="{{route('service.update', $service->id)}}" method="post">
                                        @method('PUT')
                                        @csrf
										<div class="form-group-item">
											<h5 class="form-title d-none">Basic Details</h5>
											<div class="profile-picture d-none">
												<div class="upload-profile">
													<div class="profile-img">
														<img id="blah" class="avatar" src="" alt="profile-img">
													</div>
													<div class="add-profile">
														<h5>Upload a New Photo</h5>
														<span id="imageName"></span>
													</div>
												</div>
												<div class="img-upload">
													<label class="btn btn-upload">
														Upload <input type="file">
													</label>
													<a class="btn btn-remove d-none">Remove</a>
												</div>										
											</div>
											<div class="row">
												<div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Name <span class="text-danger">*</span></label> 
														<input type="text" name="name" class="form-control" placeholder="Enter Name" value="{{ $service->name }}" required autocomplete="off">
													</div>
												</div>
												
												<div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Phone<span class="text-danger">*</span> </label>
														<div class="input-group">
															<select name="country_code" id="country_code" class="form-select phoneCode" style="max-width:110px;">
																@foreach (country_codes() as $key => $data)
																	<option  value="{{$key}}" {{ $service->country_code == $key ? 'selected' : '' }} data-show="{{$data['flag'].' '.$data['code']}}" data-showdefault="{{$data['flag'].' '.$data['code'].' '.$data['name']}}">{{$data['flag'].' '.$data['code']}}</option>
																@endforeach
															</select>
															<input type="text"  class="form-control" placeholder="Phone Number" name="phone" value="{{ $service->phone }}" required autocomplete="off">
														</div>
													</div>
												</div>
												<div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3 " >
														<label>Email </label>
														<input type="email" name="email" class="form-control" placeholder="Enter Email Address" value="{{ $service->email }}" autocomplete="off">
													</div>											
												</div>

                                                <div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Address </label>
														<textarea type="text"  class="form-control" placeholder="Address" name="address" autocomplete="off">{{ $service->address }}</textarea>
													</div>
												</div>

                                                <div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Product Name <span class="text-danger">*</span></label>
														<!-- <input type="text"  class="form-control" placeholder="Product Name" name="product_name" value="{{ $service->product_name }}" required> -->
														<select name="product_name" id="" class="form-control js-example-basic-single" required>
															<option value=""></option>
															@foreach ($products as $product)
																<option value="{{$product->id}}" {{ strpos($service->product_name, $product->name) !== false ? 'selected' : ''}}>{{$product->name}}</option>
															@endforeach
														 </select>
													</div>
												</div>

                                                <div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Product EMEI or Serial number </label>
														<input type="text"  class="form-control" placeholder="Product EMEI or Serial number" name="product_number" value="{{ $service->product_number }}" autocomplete="off">
													</div>
												</div>

                                                <div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Service Details </label>
														<textarea type="text"  class="form-control" placeholder="Service Details" name="details" autocomplete="off">{{ $service->details }}</textarea>
													</div>
												</div>

                                                <div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Warranty Duration (In days) <span class="text-danger">*</span></label>
                                                        <input type="number"  class="form-control" placeholder="Warranty Duration" name="warranty_duration" value="{{ $service->warranty_duration }}" autocomplete="off">
													</div>
												</div>

                                                <div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Repaired By <span class="text-danger">*</span></label>
                                                        <Select class="form-select" name="repaired_by" required>
                                                            <option value="">--Select--</option>
                                                            @foreach ($serviceMans as $key =>  $user)
															<option value="{{$key}}" {{ $service->repaired_by == $key ? 'selected' : '' }}>{{$user}}</option>
                                                            @endforeach
                                                        </Select>
													</div>
												</div>

												<div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Price <span class="text-danger">*</span></label>
                                                        <input onchange="calculateDue()" type="text"  class="form-control" placeholder="Price" id="total" name="total" value="{{ $service->total }}" required autocomplete="off">
													</div>
												</div>

												<div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Discount </label>
                                                        <input type="number" onchange="calculateDue()"  class="form-control" placeholder="Discount" id="discount" name="discount" value="{{ $service->discount }}" autocomplete="off">
													</div>
												</div>

												<div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Bill <span class="text-danger">*</span></label>
                                                        <input onchange="calculateDue()" type="text"  class="form-control" placeholder="Price" id="bill" name="bill" value="{{ $service->bill }}" required autocomplete="off" readonly>
													</div>
												</div>

												<div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Paid Amount</label>
                                                        <input type="number"  class="form-control" placeholder="Paid Amount" id="paid_amount" name="paid_amount" value="{{ $service->paid_amount }}" readonly autocomplete="off">
													</div>
												</div>

												<div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Due Amount</label>
                                                        <input type="number"  class="form-control" placeholder="Due Amount" id="due_amount" name="due_amount" value="{{ $service->due_amount }}" readonly autocomplete="off">
													</div>
												</div>
												
												
											</div>
										</div>
																	
										<div class="add-customer-btns text-left">
											<button type="submit" class="btn customer-btn-save">Update</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>


				<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
	
    $('.js-example-basic-single').select2({
		tags: true,
	});

	$('.js-example-basic-single-no-new-value').select2({
	});
  });

  function calculateDue(){
	// var bill = (document.getElementById("bill").value.trim() * 1)??0;
	var total = (document.getElementById("total").value.trim() * 1)??0;
	var discount = (document.getElementById("discount").value.trim() * 1)??0;
	document.getElementById("bill").value = Math.max(total-discount, 0);
	var bill = (document.getElementById("bill").value.trim() * 1)??0;
	var paid_amount = (document.getElementById("paid_amount").value.trim() * 1)??0;
	
	document.getElementById("due_amount").value = Math.max(0, bill-paid_amount);
  }
</script>
<script>
  const selectElements = document.querySelectorAll('.phoneCode');
  selectElements.forEach(selectElement => {
    selectElement.addEventListener('focus', function() {
		// console.log('open');
		const options = selectElement.options;
		Array.from(options).forEach(option => {
			option.text = option.dataset.showdefault;
		});
    });
    selectElement.addEventListener('blur', function() {
		// console.log('close')
		const options = selectElement.options;
		Array.from(options).forEach(option => {
			option.text = option.dataset.show;
		});
    });

	selectElement.addEventListener('change', function() {
		// console.log('close')
		const options = selectElement.options;
		Array.from(options).forEach(option => {
			option.text = option.dataset.show;
		});
		selectElement.blur();
    });

	selectElement.addEventListener('mousedown', function(event) {
		// console.log('close')
		const options = selectElement.options;
		Array.from(options).forEach(option => {
			option.text = option.dataset.show;
		});
		selectElement.blur();
  	});

	// Handling touchend event for touch devices
    selectElement.addEventListener('touchend', function(event) {
        // console.log('close');
        const options = selectElement.options;
        Array.from(options).forEach(option => {
            option.text = option.dataset.show;
        });
        selectElement.blur();
    });
  });
</script>

@endsection