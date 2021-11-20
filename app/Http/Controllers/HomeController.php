<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Http\Requests\CreateAppointment;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $doctors = User::where('role', 'doctor')->pluck('name','id');
        return view('guest.register', ['doctors' => $doctors]);
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
            'email' => $request['email'],
            'doctor_id' => $request['doctor_id'],
            'appointment_date' => $request['appointment_date']
        ]);
        
        /* insert status of appointment */
        $status = AppointmentStatus::create([
            'appointment_id' => $appointment->id,
            'status' => 'pending'
        ]);
        
        $appointment->appointment_status_id = $status->id;
        $appointment->save();
   
        return redirect()->route('guest.create')
                ->with('success','Appointment booked successfully.');
    }
}
