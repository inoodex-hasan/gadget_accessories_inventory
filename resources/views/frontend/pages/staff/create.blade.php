@extends('frontend.layouts.app')

@section('content')
<div class="content container-fluid">
<div class="page-header">
						<div class="content-page-header">
							<h5>Create Staff</h5>
						</div>	
					</div>
            @if ($errors->any())
                <div class="alert alert-danger" id="validation-error-alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

                <script>
                    // Set a timeout to hide the alert after 2000 milliseconds (2 seconds)
                    setTimeout(function () {
                        document.getElementById('validation-error-alert').style.display = 'none';
                    }, 3000);
                </script>
            @endif
            <div class="row">
                <div class="col-lg-12">
                  <div class="card">
                    <div class="card-header align-items-center d-flex">
                      <h4 class="card-title mb-0 flex-grow-1"></h4>
                      <div class="flex-shrink-0">
                        <div class="form-check form-switch form-switch-right form-switch-md">
                            <a href="{{ route('staff.index') }}" class="btn btn-info" style="background: #fe3727 !important; color: #fff; border:none;">Staff List</a>
                        </div>
                      </div>
                    </div>
                    <!-- end card header -->
                    <div class="card-body">
                      <div class="live-preview">
                        <div class="row gy-4">
                            <form action="{{ route('staff.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">                                    
                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" value="{{ old('name') }}" id="name" name="name" placeholder="Enter User name" >
                                    </div>                                    
                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" class="form-control" value="{{ old('email') }}" id="email" name="email" placeholder="Enter User email" >
                                    </div>
                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control" value="{{ old('phone') }}" id="phone" name="phone" placeholder="Enter User phone" >
                                    </div>
                            
                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="department" class="form-label">Department</label>
                                        <input type="text" class="form-control" value="{{ old('department') }}" id="department" name="department" placeholder="Enter Department">
                                    </div>
                            
                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="designation" class="form-label">Designation</label>
                                        <input type="text" class="form-control" value="{{ old('designation') }}" id="designation" name="designation" placeholder="Enter Designation">
                                    </div>
                            
                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="joining_date" class="form-label">Joining Date</label>
                                        <input type="date" class="form-control" value="{{ old('joining_date') }}" id="joining_date" name="joining_date">
                                    </div>
                            
                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="salary" class="form-label">Salary</label>
                                        <input type="number" class="form-control" value="{{ old('salary') }}" id="salary" name="salary" placeholder="Enter Salary" min="1">
                                    </div>
                            
                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="documents" class="form-label">Necessary Documents</label>
                                        <input type="file" multiple class="form-control" id="documents" name="documents[]">
                                    </div>
                            
                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="image" class="form-label">Image (366x366)</label>
                                        <input type="file" multiple class="form-control" id="image" name="images">
                                    </div>
                            
                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select mb-3" name="status">
                                            <option selected="" value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>                                            
                                </div>
                                <button type="submit" class="btn btn-primary float-end">Submit</button>
                            </form>
                            

                        </div>
                        <!--end row-->
                      </div>
                    </div>
                  </div>
                </div>
                <!--end col-->
              </div>

        </div>
        <!-- container-fluid -->

@section('script')
<script>
    ClassicEditor
    .create(document.querySelector('#editor'))
    .catch(error => {
        console.error(error);
    });

</script>
@endsection
@endsection
