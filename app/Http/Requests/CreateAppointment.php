<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class CreateAppointment extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userId = '';
        if(Auth::check()) {
            $userId = Auth::user()->id;
        }
        return [
            'email' => ($userId) ? 'nullable' : 'required|email',
            'doctor_id' => ($userId) ? 'required' : 'nullable',
            'patient_id' => ($userId) ? 'required' : 'nullable',
            'appointment_date' => 'required'
        ];
    }
    
    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'Email is required',
            'email.email' => 'Enter email in proper format',
            'doctor_id.required' => 'Doctor is required',
            'patient_id.required' => 'Patient is required',
            'appointment_date.required' => 'Appointment date is required'
        ];
    }
}
