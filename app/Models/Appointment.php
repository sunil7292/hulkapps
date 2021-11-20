<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'patient_id', 'doctor_id', 'appointment_status_id', 'email', 'appointment_date'
    ];
    
    public function appointmentGuest() {
        return $this->belongsTo("App\Models\User", 'patient_id', 'id');
    }
    
    public function appointmentPatient() {
        return $this->belongsTo("App\Models\User", 'patient_id', 'id');
    }
    
    public function appointmentDoctor() {
        return $this->belongsTo("App\Models\User", 'doctor_id', 'id');
    }
    
    public function appointmentStatus() {
        return $this->belongsTo("App\Models\AppointmentStatus", 'appointment_status_id', 'id');
    }
}
