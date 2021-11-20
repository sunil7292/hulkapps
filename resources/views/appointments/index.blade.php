@extends('layouts.appNew')

@section('title', 'Appointment')

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
    <script src="{{ asset('custom/application.js') }}"></script>
@stop

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Appointment</h1>
            </div>
            @if (auth::user()->role == 'admin')
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a class="btn btn-primary" href="{{ route('appointments.create') }}">Add New Appointment</a></li>
                </ol>
            </div>
            @endif
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
                                        placeholder="Search"> <span class="input-group-btn">
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
                                    <th>Doctor</th>
                                    @if (auth::user()->role != 'patient')
                                    <th>Patient/Email</th>
                                    @endif
                                    <th>Appointment</th>
                                    <th>Status</th>
                                    @if (auth::user()->role != 'patient')
                                    <th>Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($allAppointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->doctor }}</td>
                                    @if (auth::user()->role != 'patient')
                                    <td>@if ($appointment->patient == '') {{ $appointment->email }} @else {{ $appointment->patient }} @endif</td>
                                    @endif
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('m-d-Y h A') }}</td>
                                    <td id='{{ $appointment->id }}'>{{ $appointment->status }}</td>
                                    @if (auth::user()->role != 'patient')
                                    <td>
                                        <a class="btn btn-info btn-sm" href="{{ route('appointments.edit', $appointment->id) }}">
                                            <i class="fas fa-pencil-alt">
                                            </i>
                                            Edit
                                        </a><br /><br />
                                        <a class="btn btn-success btn-sm" onclick="appointmentStatus({{ $appointment->id }}, 'confirm');">
                                            Confirm
                                        </a>
                                        <a class="btn btn-danger btn-sm" onclick="appointmentStatus({{ $appointment->id }}, 'decline');">
                                            Decline
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                    <div class="row">
                                        <p>No Appointment in system</p>
                                    </div>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <ul class="pagination pagination-sm m-0 float-right">
                            {!! $allAppointments->withQueryString()->onEachSide(2)->links() !!}
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
