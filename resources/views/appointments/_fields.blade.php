<div class="card-body">
    <div class="form-group">
        <label for="name">Doctor:</label>
        {!! Form::select('doctor_id', $doctors, $appointment->doctor_id, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        <label for="name">Patient:</label>
        {!! Form::select('patient_id', $patients, $appointment->patient_id, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        <label>Appointment Time:</label>
        <div class="input-group date" id="reservationdate" data-target-input="nearest">
            <input type="text" name='appointment_date' class="form-control datetimepicker-input" style="pointer-events: none;" 
                   @if ($appointment->appointment_date != '')
                   value="{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('m-d-Y H A') }}"
                   @endif
                   />
            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
            </div>
        </div>
    </div>
    @if (Route::currentRouteName() == 'doctors.create')
    <div class="form-group">
        <label for="first_name">Password:</label>
        <input type="password" class="form-control" id="password" placeholder="Enter Password" name="password">
    </div>
    <div class="form-group">
        <label for="first_name">Confirm Password:</label>
        <input type="text" class="form-control" id="password-confirm" placeholder="Enter Confirm Password" name="password_confirmation" required>
    </div>
    @endif
    
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary">Submit</button>
</div>
                        
       