<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\CreateDoctor;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Appointment;

class DoctorController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $allDoctors = User::where('role', 'doctor');
        
        $search =  $request->input('q');
        if($search != "") {
            $allDoctors = $allDoctors->where(function ($query) use($search)
            {
                $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('contact_no', 'like', '%'.$search.'%');
            });
        }
        
        $allDoctors = $allDoctors->paginate((int)env('PER_PAGE'));
        return view('doctors.index', ['allDoctors' => $allDoctors]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $doctor = new User();
        return view('doctors.create',['doctor' => $doctor]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\CreateDoctor $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateDoctor $request)
    {   
        $request = $request->all();
        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'role' => 'doctor',
            'contact_no' => $request['contact_no'],
            'password' => Hash::make($request['password']),
        ]);
   
        return redirect()->route('doctors.index')
                ->with('success','Doctor created successfully.');
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User $doctor
     * @return \Illuminate\Http\Response
     */
    public function edit(User $doctor)
    {
        return view('doctors.edit',['doctor' => $doctor]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\CreateDoctor  $request
     * @param  \App\Models\User  $doctor
     * @return \Illuminate\Http\Response
     */
    public function update(CreateDoctor $request, User $doctor)
    {
        $doctor->update($request->all());
  
        return redirect()->route('doctors.index')
            ->with('success','Doctor updated successfully');
        
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $doctor)
    {
        $appointment = Appointment::where('doctor_id', $doctor->id)->first();
        if (isset($appointment->id) && $appointment->id > 0) {
            return redirect()->route('doctors.index')
                ->with('error','Appointment assign to doctor, you can not delete.');
        } else {
            $doctor->delete();

            return redirect()->route('doctors.index')
                ->with('success','Doctor deleted successfully');
        }
    }
}
