@extends('layouts.appNew')

@section('title', 'Patients')

@section('css')
    @parent
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('adminLTE/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('adminLTE/plugins/toastr/toastr.min.css') }}">
@stop

@section('js')
    @parent
    <!-- SweetAlert2 -->
    <script src="{{ asset('adminLTE/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('adminLTE/plugins/toastr/toastr.min.js') }}"></script>
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Patient</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a class="btn btn-primary" href="{{ route('patients.create') }}">Add New Patient</a></li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <form action="" method="GET" role="search">
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="q"
                                        placeholder="Search atients"> <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">
                                            <span class="glyphicon glyphicon-search">Search</span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <div>
                            @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <span>{!! \Session::get('success') !!}</span>
                            </div>
                            @endif
                            @if (\Session::has('error'))
                            <div class="alert alert-danger">
                                <span>{!! \Session::get('error') !!}</span>
                            </div>
                            @endif
                        </div>
                        <table class="table table-bordered">
                            <thead>                  
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($allPatients as $patients)
                                <tr>
                                    <td>{{ $patients->name }}</td>
                                    <td>{{ $patients->email }}</td>
                                    <td>{!! html_entity_decode($patients->contact_no) !!}</td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{ route('patients.edit', $patients->id) }}">
                                            <i class="fas fa-pencil-alt">
                                            </i>
                                            Edit
                                        </a>
                                        
                                        <a class="btn btn-danger btn-sm" href="" 
                                           onclick="event.preventDefault(); if (confirm('Are you sure you want to delete this record?')) { 
                                               document.getElementById('deletePatient').submit();
                                           } else {
                                               return false;
                                           }">
                                            <i class="fas fa-trash">
                                            </i>
                                            Delete
                                            <form action="{{ route('patients.destroy',$patients->id) }}" id='deletePatient' method="POST">

                                                @csrf
                                                @method('DELETE')

                                            </form>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                    <div class="row">
                                        <p>No Patient in system</p>
                                    </div>
                                @endforelse
                                
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <ul class="pagination pagination-sm m-0 float-right">
                            {!! $allPatients->withQueryString()->onEachSide(2)->links() !!}
                        </ul>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
    <div id="modelDiv"></div>
</section>
@endsection
