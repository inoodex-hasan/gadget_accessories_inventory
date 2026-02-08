@extends('frontend.layouts.app')

@section('content')
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="content-page-header">
                <h5>Role List</h5>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">

                    @if (session('sweet_alert'))
                        <script>
                            Swal.fire({
                                icon: '{{ session('sweet_alert.type') }}',
                                title: '{{ session('sweet_alert.title') }}',
                                text: '{{ session('sweet_alert.text') }}',
                            });
                        </script>
                    @endif

                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1"></h4>
                        <div class="flex-shrink-0">
                            <a href="{{ route('role.create') }}" class="btn btn-info">Create Role</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users->chunk(2) as $chunk)
                                        <tr>
                                            @foreach ($chunk as $item)
                                                <td>{{ $loop->parent->index * 2 + $loop->index + 1 }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-1">
                                                        <a href="{{ route('role.edit', $item->id) }}"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="fe fe-edit text-white"></i>
                                                        </a>
                                                        <button type="button" data-bs-toggle="modal"
                                                            data-bs-target="#myModal{{ $item->id }}" class="btn btn-sm btn-danger">
                                                            <i class="fe fe-trash-2 text-white"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            @endforeach

                                            @if ($chunk->count() < 2)
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Modals -->
        @foreach ($users as $item)
            <div id="myModal{{ $item->id }}" class="modal fade" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Role</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this Role:
                            <strong style="color: darkorange">{{ $item->name }}</strong>?
                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('role.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection

@section('script')
@endsection