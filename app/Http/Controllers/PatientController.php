<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\CreatePatient;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Appointment;

class PatientController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $allPatients = User::where('role', 'patient');
        
        $search =  $request->input('q');
        if($search != "") {
            $allPatients = $allPatients->where(function ($query) use($search)
            {
                $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('contact_no', 'like', '%'.$search.'%');
            });
        }
        
        $allPatients = $allPatients->paginate((int)env('PER_PAGE'));
        return view('patients.index', ['allPatients' => $allPatients]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        if(!Auth::user()->isAdmin())
//            return redirect()->route('welcomePage');
        $patient = new User();
        return view('patients.create',['patient' => $patient]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\CreatePatient $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePatient $request)
    {   
//        if(!Auth::user()->isAdmin())
//            return redirect()->route('welcomePage');
        $request = $request->all(); //echo '<pre>'; print_r($request); exit;
        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'role' => 'patient',
            'contact_no' => $request['contact_no'],
            'password' => Hash::make($request['password']),
        ]);
   
        return redirect()->route('patients.index')
                ->with('success','patient created successfully.');
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(User $patient)
    {
        return view('patients.edit',['patient' => $patient]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\CreatePatient  $request
     * @param  \App\Models\User $patient
     * @return \Illuminate\Http\Response
     */
    public function update(CreatePatient $request, User $patient)
    {
        $patient->update($request->all());
  
        return redirect()->route('patients.index')
            ->with('success','patient updated successfully');
        
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $patient)
    {
        $appointment = Appointment::where('patient_id', $patient->id)->first();
        if (isset($appointment->id) && $appointment->id > 0) {
            return redirect()->route('patients.index')
                ->with('error','Appointment assign to patient, you can not delete.');
        } else {
            $patient->delete();

            return redirect()->route('patients.index')
                ->with('success','patient deleted successfully');
        }
    }
}
