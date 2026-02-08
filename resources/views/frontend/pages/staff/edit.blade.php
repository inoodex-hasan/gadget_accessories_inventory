@extends('frontend.layouts.app')

@section('content')
<div class="content container-fluid">
    <div class="page-header">
        <div class="content-page-header">
            <h5>Edit Staff</h5>
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
                        <a href="{{ route('staff.index') }}" class="btn btn-info">Staff List</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="live-preview">
                        <div class="row gy-4">
                            <form action="{{ route('staff.update', $user->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" value="{{ old('name', $user->name) }}" id="name" name="name" placeholder="Enter staff name">
                                    </div>                                  

                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" value="{{ old('email', $user->email) }}" id="email" name="email" placeholder="Enter staff email">
                                    </div>

                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control" value="{{ old('phone', $user->phone) }}" id="phone" name="phone" placeholder="Enter staff phone">
                                    </div> 

                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="department" class="form-label">Department</label>
                                        <input type="text" class="form-control" value="{{ old('department', $user->department) }}" id="department" name="department" placeholder="Enter department">
                                    </div>

                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="designation" class="form-label">Designation</label>
                                        <input type="text" class="form-control" value="{{ old('designation', $user->designation) }}" id="designation" name="designation" placeholder="Enter designation">
                                    </div>

                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="joining_date" class="form-label">Joining Date</label>
                                        <input type="date" class="form-control" value="{{ old('joining_date', $user->joining_date) }}" id="joining_date" name="joining_date">
                                    </div>

                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="salary" class="form-label">Salary</label>
                                        <input type="number" class="form-control" value="{{ old('salary', $user->salary) }}" id="salary" name="salary" placeholder="Enter salary">
                                    </div>

                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="image" class="form-label">Profile Image (366x366)</label>
                                        <input type="file" class="form-control" id="image" name="images">
                                    </div>

                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="documents" class="form-label">Upload Necessary Documents</label>
                                        <input type="file" class="form-control" id="documents" name="documents[]" multiple>
                                    </div>

                                    <div class="col-xxl-3 col-md-6 mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select mb-3" name="status">
                                            <option {{ $user->status == '1' ? 'selected' : '' }} value="1">Active</option>
                                            <option {{ $user->status == '0' ? 'selected' : '' }} value="0">Inactive</option>
                                        </select>
                                    </div>

                                </div>

                                <button type="submit" class="btn btn-primary float-end">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>   
</div>

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
