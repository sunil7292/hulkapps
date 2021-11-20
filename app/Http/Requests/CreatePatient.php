<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class CreatePatient extends FormRequest
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
        $user = User::find((int) request()->segment(2));
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => isset($user->id) ? 'required|email|unique:users,email,'.$user->id.',id' : 'required|email|unique:users,email',
            'contact_no' => ['required', 'digits:10'],
            'password' => isset($user->id) ? 'nullable' : 'required|string|min:8|confirmed'
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
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'email.required' => 'Email is required',
            'email.email' => 'Enter email in proper format',
            'email.unique' => 'This email already has been taken',
            'contact_no.required' => 'Contact number is required',
            'contact_no.digits' => 'Contact number must be a digit',
        ];
    }
}
