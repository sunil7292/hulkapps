$(document).ready(function(){
    if ($('#reservationdate').length) {
        $('#reservationdate').datetimepicker({
            format: 'MM-DD-YYYY hh A',
            minDate: new Date()
        });
    }
});

function appointmentStatus(id,status) {
    if (confirm('Are you sure you want to '+status+' status of this appointment?')) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "appointments/changeStatus",
            data: {
                id : id,
                status : status
            },
            cache: false,
            type: "POST",
            dataType: 'json',
            success: function(data) {
                if(data.success == true) {
                    $('#'+id).html(status);
                } else {
                    alert(data.message);
                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    }
}