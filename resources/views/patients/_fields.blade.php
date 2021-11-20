<div class="card-body">
    <div class="form-group">
        <label for="first_name">Name:</label>
        <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name" @if($patient->name != '') value="{{ $patient->name }}" @else value="{{ old('name') }}" @endif>
    </div>
    <div class="form-group">
        <label for="first_name">Email:</label>
        <input type="email" class="form-control" id="email" placeholder="Enter EmailAddress" name="email" @if($patient->email != '') value="{{ $patient->email }}" @else value="{{ old('email') }}" @endif>
    </div>
    <div class="form-group">
        <label for="first_name">Contact:</label>
        <input type="text" class="form-control" id="contact_no" placeholder="Enter Contact" name="contact_no" @if($patient->contact_no != '') value="{{ $patient->contact_no }}" @else value="{{ old('contact_no') }}" @endif>
    </div>
    @if (Route::currentRouteName() == 'patients.create')
    <div class="form-group">
        <label for="first_name">Password:</label>
        <input type="password" class="form-control" id="password" placeholder="Enter Password" name="password">
    </div>
    <div class="form-group">
        <label for="first_name">Confirm Password:</label>
        <input type="password" class="form-control" id="password-confirm" placeholder="Enter Confirm Password" name="password_confirmation" required>
    </div>
    @endif
    
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-primary">Submit</button>
</div>
                        
       