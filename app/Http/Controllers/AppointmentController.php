<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Http\Requests\CreateAppointment;
use Carbon\Carbon;
use Auth;

class AppointmentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('isAdminOrDoctor')->except('index');
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $allAppointments = Appointment::select(DB::raw('appointments.*, udr.name AS doctor, upt.name AS patient, apst.status'))
                ->leftJoin('users AS udr', 'udr.id', '=', 'appointments.doctor_id')
                ->leftJoin('users AS upt', 'upt.id', '=', 'appointments.patient_id')
                ->leftJoin('appointment_statuses AS apst', 'apst.id', '=', 'appointments.appointment_status_id');
        if (Auth::user()->role == 'doctor') {
            $allAppointments = $allAppointments->where('appointments.doctor_id', Auth::user()->id);
        } else if (Auth::user()->role == 'patient') {
            $allAppointments = $allAppointments->where('appointments.patient_id', Auth::user()->id);
        }
        
        $search =  $request->input('q');
        if($search != "") {
            $allAppointments = $allAppointments->where(function ($query) use($search)
            {
                $query->where('udr.name', 'like', '%'.$search.'%')
                        ->orWhere('upt.name', 'like', '%'.$search.'%')
                        ->orWhere('appointments.email', 'like', '%'.$search.'%');
            });
        }
        
        $allAppointments = $allAppointments->paginate((int)env('PER_PAGE'));
        return view('appointments.index', ['allAppointments' => $allAppointments]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $appointment = new Appointment();
        $patients = User::where('role', 'patient')->pluck('name','id');
        $doctors = User::where('role', 'doctor')->pluck('name','id');
        
        return view('appointments.create', ['doctors' => $doctors, 'patients' => $patients, 'appointment' => $appointment]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\CreateAppointment $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAppointment $request)
    {   
        $request = $request->all();
        $request['appointment_date'] = substr_replace($request['appointment_date'],":00",13, 0);
        $request['appointment_date'] = Carbon::createFromFormat('m-d-Y h:i a', $request['appointment_date'])->format('Y-m-d H:i:s');
        
        $appointment = Appointment::create([
            'patient_id' => $request['patient_id'],
            'doctor_id' => $request['doctor_id'],
            'appointment_date' => $request['appointment_date']
        ]);
        
        /* insert status of appointment */
        $status = AppointmentStatus::create([
            'user_id' => Auth::user()->id,
            'appointment_id' => $appointment->id,
            'status' => 'pending'
        ]);
        
        $appointment->appointment_status_id = $status->id;
        $appointment->save();
   
        return redirect()->route('appointments.index')
                ->with('success','Appointment booked successfully.');
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Appointment $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $appointment)
    {
        $patients = User::where('role', 'patient')->pluck('name','id');
        $doctors = User::where('role', 'doctor')->pluck('name','id');
        
        return view('appointments.edit', ['doctors' => $doctors, 'patients' => $patients, 'appointment' => $appointment]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\CreateAppointment $request
     * @param  \App\Models\Appointment $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(CreateAppointment $request, Appointment $appointment)
    {
        $request = $request->all();
        $request['appointment_date'] = substr_replace($request['appointment_date'],":00",13, 0);
        $request['appointment_date'] = Carbon::createFromFormat('m-d-Y h:i a', $request['appointment_date'])->format('Y-m-d H:i:s');
        $appointment->patient_id = $request['patient_id'];
        $appointment->doctor_id = $request['doctor_id'];
        $appointment->appointment_date = $request['appointment_date'];
        $appointment->save();
  
        return redirect()->route('appointments.index')
            ->with('success','Appointment updated successfully');
        
    }
    
    /**
     * Change status of appointment.
     *
     * @param  App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $appointment = Appointment::find($request->id);
        if ($appointment->appointmentStatus->status == $request->status) {
            return response()->json( array('success' => false, 'message' => 'Status already '.$request->status));    
        }
        
        /* insert status of appointment */
        $status = AppointmentStatus::create([
            'user_id' => Auth::user()->id,
            'appointment_id' => $request->id,
            'status' => $request->status
        ]);
        
        $appointment = Appointment::find($request->id);
        $appointment->appointment_status_id = $status->id;
        $appointment->save();
  
        return response()->json( array('success' => true));
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success','Appointment deleted successfully');
    }
}
