
function initReservationsPage() {
    
    var user = AuthService.getCurrentUser();
    if (user) {
        if ($("#name").length) {
            $("#name").val(user.name || "");
        }
        if ($("#email").length) {
            $("#email").val(user.email || "");
        }
    }
    
    
    $(".reservation-form").on("submit", function(e) {
        e.preventDefault();
        createReservation();
    });
}

function createReservation() {
    var user = AuthService.getCurrentUser();
    if (!user) {
        toastr.error("Please login to make a reservation");
        window.location.href = "#page6";
        return;
    }
    
    var date = $("#date").val();
    var time = $("#time").val();
    
    if (!date || !time) {
        toastr.error("Please fill in date and time");
        return;
    }
    
    var data = {
        user_id: user.user_id,
        date: date,
        time: time
    };
    
    RestClient.post("reservations", data,
        function(result) {
            toastr.success("Reservation created successfully!");
            
            $(".reservation-form")[0].reset();
            
            setTimeout(function() {
                window.location.href = "#page9";
            }, 1000);
        },
        function(error) {
            var errorMsg = "Error creating reservation";
            if (error && error.responseJSON && error.responseJSON.message) {
                errorMsg = error.responseJSON.message;
            }
            toastr.error(errorMsg);
        }
    );
}

