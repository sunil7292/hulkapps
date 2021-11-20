<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentStatus extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'appointment_id', 'status'
    ];
    
    public function statusUser() {
        return $this->belongsTo("App\Models\User", 'user_id', 'id');
    }
}
