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
select, input{
	border-color: #000 !important;
}
label{
	color: #000 !important;
}
.select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #aaa;
    border-radius: 4px;
    width: 55%!important;
}
</style>
<form action="{{route('sales.update', $sales->id)}}" method="post">
    @csrf
	@method('PUT')
<div class="content container-fluid pt-0">
					<div class="card mb-3">
						<div class="card-body">
							<!-- Page Header -->
							<div class="page-header mb-3">
								<div class="content-page-header mb-3">
									<h6>Customer Info</h5>
								</div>	
							</div>
							<!-- /Page Header -->				
							<div class="row">
								<div class="col-md-12">
									
										<div class="form-group-item mb-0 pb-0">
											<h5 class="form-title d-none">Basic Details</h5>
											<div class="row">
												<div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Name <span class="text-danger">*</span></label> 
														<input type="text" name="name" class="form-control p-2" placeholder="Enter Name" value="{{$customer->name }}" required autocomplete="off">
													</div>
												</div>
												

                                            
                                                <div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Phone <span class="text-danger">*</span></label>
                                                       <input type="tel" class="form-control p-2" name="phone" id="phone" pattern="[0-9]{11}" value="{{$customer->phone }}" maxlength="11" placeholder="Enter phone number" required>
													</div>
												</div>

                                                <div class="col-lg-4 col-md-6 col-sm-12">
													<div class="input-block mb-3">
														<label>Address <span class="text-danger">*</span></label>
                                                        <input type="text"  class="form-control p-2" placeholder="Enter Address" id="address" name="address" value="{{ $customer->address }}" required autocomplete="off">
													</div>
												</div>

											</div>
										</div>

									
								</div>
							</div>
						</div>
					</div>

					<div class="card mb-0">
						<div class="card-body">
							<!-- Page Header -->
							<div class="page-header mb-3">
								<div class="content-page-header mb-3">
									<h6>Cart Info</h6>
								</div>	
							</div>
							<!-- /Page Header -->				
							<div class="row">
								<div class="col-md-12">
									<form action="{{route('sales.store')}}" method="post">
                                        @csrf
										



										<style>
											@media (min-width: 768px) {
												.col-md-2 {
													width: 13% !important;
													padding-left: 5px;
													padding-right: 5px;
												}
											}
										</style>

										<div class="group-item" data-itemnumber="1" id="form-group-item1" style="background:#198754; color:#fff !important; padding: 10px 5px;">
											<div class="row align-items-end">
												<div class="col-md-3">
													<label style="color:#fff !important;">Product Name</label>
													<select onchange="selectProduct(1)" id="product1" class="form-control js-example-basic-single" style="height: 30px;" >
														<option value=""></option>
														@foreach ($products as $product)
															<option value="{{ $product->id }}" data-price="{{ $product->latestPurchase->unit_price??0 }}"  data-warranty="{{ $product->warranty??0 }}">
																{{ $product->name }}({{$product->model}})
															</option>
														@endforeach
													</select>
												</div>
												<div class="col-md-2">
													<label style="color:#fff !important;"> Warranty</label>
													<input type="number" id="warranty1" style="height: 30px;" class="form-control" readonly>
												</div>
												<div class="col-md-2">
													<label style="color:#fff !important;"> Purchase Price</label>
													<input type="number" id="purchase_price1" style="height: 30px;" class="form-control" readonly>
												</div>
												<div class="col-md-2">
													<label style="color:#fff !important;"> Unit Price</label>
													<input onchange="calculateTotal()" type="number" id="unit_price1" style="height: 30px;" class="form-control unit-price" >
												</div>
												<div class="col-md-2">
													<label style="color:#fff !important;">Qty</label>
													<input onchange="calculateTotal()" type="number" id="qty1" style="height: 30px;" class="form-control qty" min="0">
												</div>
												<div class="col-md-2">
													<label style="color:#fff !important;">Total</label>
													<input type="number" id="total1" style="height: 30px;" class="form-control total" readonly>
												</div>
												<div class="col-md-1 text-end btn-holder">
													<button onclick="addItem()"  type="button" class=" btn btn-sm btn-primary addItemBtn">Add</button>
												</div>
											</div>
										</div>

										<hr>

									<div class=""  style="color:#000 !important;">
										<div class="row align-items-end">
											<div class="col-md-4">
												<label style="color:#000 !important;">Product Name</label>
											</div>
											<div class="col-md-2">
												<label style="color:#000 !important;"> Unit Price</label>
											</div>
											<div class="col-md-2">
												<label style="color:#000 !important;">Qty</label>
											</div>
											<div class="col-md-2">
												<label style="color:#000 !important;">Total</label>
											</div>
											<div class="col-md-1 text-end btn-holder">
											</div>
										</div>
									</div>
										
									<div id="item_container">
										@foreach ($items as $item)
										<div class="item{{$item->product_id}} group-item mt-2" data-itemnumber="{{$loop->index+2}}" id="form-group-item{{$loop->index+2}}">
											<div class="row align-items-end">
												<div class="col-md-4">
													<input  type="hidden" name="product[]" value="{{$item->product_id}}">
													<select onchange="selectProduct({{$loop->index+2}})" style="height: 30px;"  id="product{{$loop->index+2}}" class="product{{$item->product_id}} form-control  d-none" required disabled>
														<option value=""></option>
														@foreach ($products as $product)
															<option value="{{ $product->id }}" data-price="{{ $product->latestPurchase->unit_price??0 }}" {{$item->product_id == $product->id ? 'selected' : ''}}>
																{{ $product->name }}({{$product->model}})
															</option>
														@endforeach
													</select>

													@foreach ($products as $product)

														@if($item->product_id == $product->id)
														<p>{{ $product->name }}({{$product->model}})</p>
														@endif
													@endforeach
												</div>
												<div class="col-md-2">
													<input onchange="calculateTotal()" type="number" name="unit_price[]" id="unit_price{{$loop->index+2}}" style="height: 30px;" class="form-control unit-price" value="{{$item->unit_price}}" >
												</div>
												<div class="col-md-2">
													<input onchange="calculateTotal()" type="number" name="qty[]" id="qty{{$loop->index+2}}" style="height: 30px;" class="qty{{$item->product_id}} form-control qty" min="0" value="{{$item->qty}}">
												</div>
												<div class="col-md-2">
													<input type="number" name="total" id="total{{$loop->index+2}}" style="height: 30px;" class="form-control total"  value="{{$item->total_price}}" readonly>
												</div>
												<div class="col-md-2 text-end btn-holder">
													<button onclick="removeItem({{$loop->index+2}})" type="button" class="btn btn-danger remove-item me-1 ">×</button>
												</div>
											</div>
										</div>
										@endforeach
									</div>
									<hr>

									<br>
									<div id="summerySection" class="row d-flef justify-content-end align-items-end">
										<div class="col-md-4"></div>
										<div class="col-md-2">
											<label>Sub Total</label>
											<input onchange="calculateTotal()" type="number" id="subTotal" name="subTotal" style="height: 30px;" class="form-control total" value="{{$sales->bill}}" readonly>
										</div>
										<div class="col-md-2">
											<label>Discount</label>
											<input onchange="calculateTotal()" type="number" id="discount" name="discount" style="height: 30px;" class="form-control total" value="{{$sales->discount}}">
										</div>
										<div class="col-md-2">
											<label>Grand Total</label>
											<input type="number" id="grandTotal" name="grandTotal" style="height: 30px;" class="form-control total" value="{{$sales->payble}}" readonly>
										</div>
										<div class="col-md-2 text-end btn-holder">
											
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

</form>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script>
  var itemNumber = {{count($items)+2}};

  $(document).ready(function() {
    $('.js-example-basic-single').select2({
      tags: true
    });


  });

    function addItem(){

		var product = document.getElementById('product1').value;
		var qty = document.getElementById('qty1').value;
		if(product==""){
			document.getElementById('product1').setCustomValidity("Time is required");
        	document.getElementById('product1').reportValidity();
			return;
		}

		let selectedName = document.getElementById('product1').options[document.getElementById('product1').selectedIndex].text;


		if(qty.trim() === ""){
			document.getElementById('qty1').setCustomValidity("Time is required");
        	document.getElementById('qty1').reportValidity();
			return;
		}

		const price = document.getElementById('unit_price1').value;
		if(price.trim() === ""){
			document.getElementById('unit_price1').setCustomValidity("Time is required");
        	document.getElementById('unit_price1').reportValidity();
			return;
		}


		var eles = document.getElementsByClassName('item'+product);
		if(eles.length){
			var qEles = document.getElementsByClassName('qty'+product);
			if(qEles.length){
				var old_qty = qEles[0].value;
				qEles[0].value = parseInt(old_qty) + parseInt(qty);
			}

		}else{

			

			var html = `
				<div class="item${product} group-item mt-2" data-itemnumber="${itemNumber}" id="form-group-item${itemNumber}">
					<div class="row align-items-end">
						<div class="col-md-4">
							<input  type="hidden" name="product[]" value="${product}">
							<select onchange="selectProduct(${itemNumber})" style="height: 30px;"  id="product${itemNumber}" class="product${product} form-control product-select js-example-basic-single d-none" required disabled>
								<option value=""></option>
								@foreach ($products as $product)`;

								var select = (product == {{ $product->id }} ? 'selected' : '');
								
								html +=`
									<option value="{{ $product->id }}" data-price="{{ $product->latestPurchase->unit_price??0 }}" ${select}>
										{{ $product->name }}({{$product->model}})
									</option>
								@endforeach
							</select>
							<p>${selectedName}</p>
						</div>
						<div class="col-md-2">
							<input onchange="calculateTotal()" type="number" name="unit_price[]" id="unit_price${itemNumber}" style="height: 30px;" class="form-control unit-price" value="${price}" >
						</div>
						<div class="col-md-2">
							<input onchange="calculateTotal()" type="number" name="qty[]" id="qty${itemNumber}" style="height: 30px;" class="qty${product} form-control qty" min="0" value="${qty}">
						</div>
						<div class="col-md-2">
							<input type="number" name="total" id="total${itemNumber}" style="height: 30px;" class="form-control total" readonly>
						</div>
						<div class="col-md-2 text-end btn-holder">
							<button onclick="removeItem(${itemNumber})" type="button" class="btn btn-danger remove-item me-1 ">×</button>
						</div>
					</div>
				</div>
			`;
			$('#item_container').append(html);
			itemNumber++;
		}

		calculateTotal();
      	
	}

	function removeItem(item){
		document.getElementById('form-group-item' + item).remove();
		calculateTotal();
	}

 function selectProduct(item){
	
	var selectedPrice = $('#product'+item+' option:selected').data('price');
	var selectedWarranty = $('#product'+item+' option:selected').data('warranty');
	if(document.getElementById('purchase_price' + item))
		document.getElementById('purchase_price' + item).value = selectedPrice;
	if(document.getElementById('warranty' + item))
		document.getElementById('warranty' + item).value = selectedWarranty;
	calculateTotal()
}

  	function calculateTotal(){
		var eles = document.getElementsByClassName('group-item');

		var subTotal = 0;
		for(var i=0; i<eles.length; i++){
			var itemNumber = eles[i].dataset.itemnumber;
			
			var unit_price = document.getElementById('unit_price'+itemNumber).value;
			var qty = document.getElementById('qty'+itemNumber).value;
			var totalEle = document.getElementById('total'+itemNumber);
			
			if(parseInt(qty) >= 0 &&  parseFloat(unit_price) >= 0){
				var total = (parseInt(qty) * parseFloat(unit_price));
				totalEle.value = total;
				if(i>0)subTotal += total;
			}
			

		}
		var discount = document.getElementById('discount').value;
		discount = (parseFloat(discount) >= 0 ? parseFloat(discount) : 0);
		if(discount > subTotal) discount = subTotal;
		document.getElementById('discount').value = discount;

		document.getElementById('subTotal').value = subTotal;
		document.getElementById('grandTotal').value = subTotal - discount;

		var content = document.getElementById("item_container").innerHTML;
		content = content.trim();
		if(content != ""){
			document.getElementById("summerySection").classList.remove('d-none');
		}else{
			document.getElementById("summerySection").classList.add('d-none');
		}
  	}
</script>


<!-- 1) dashboard value
2) sideber scrolling
3) rename laber 'warranty' to 'warranty(dayes)' on product add
4) invoice download as pdf
5)order details page.
6) rename laber 'total price' to 'total price (payble)' on purchase
7) warranty count down on order details. -->










@endsection